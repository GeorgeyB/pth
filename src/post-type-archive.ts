import { Filter, GetPostsParams, GetPostsPayload, State } from "./types";
import debounce from "debounce";

function getUrlQuery() {
  const match = window.location.href.match("q/(.*)");

  if (!match) {
    return "";
  }

  return match[1];
}

function buildQueryFromFilters(filters: Filter[]) {
  const filtersObj = filters.reduce<{ [key: string]: string[] }>(
    (acc, curr) => ({
      ...acc,
      [curr.taxonomy]: [...(acc[curr.taxonomy] || []), curr.term],
    }),
    {}
  );

  return Object.keys(filtersObj)
    .sort()
    .map((taxonomy) => {
      return `${taxonomy}/${filtersObj[taxonomy]
        .sort()
        // TODO underscore replace hack, should really fetch the
        // real taxonomy slug from somewhere instead
        .map((term) => `${term}`)
        .join("/")}`;
    })
    .join("/");
}

jQuery(function ($) {
  const config = {
    containerSelector: ".posts",
    heroSelector: ".hero",
    heroBottomSelector: ".hero-bottom",
    pageSelector: ".page",
    itemSelector: ".box",
    filtersContainerSelector: ".filters-container",
    filterSelector: ".filter",
    loadMoreContSelector: ".load-more",
    loadMoreSelector: ".load-more a",
  };

  const state: State = {
    count: 0,
    page: 1,
    total: 0,
    totalPages: 1,
    filters: [],
  };

  function setState(obj: Partial<State>) {
    Object.assign(state, obj);

    if (state.$loadMore) {
      state.$loadMore.toggle(state.totalPages > state.page);
    }
  }

  function init() {
    setState({
      $cont: $(config.containerSelector),
      $hero: $(config.heroSelector),
      $heroBottom: $(config.heroBottomSelector),
      $page: $(config.pageSelector),
      $filtersContainer: $(config.filtersContainerSelector),
      $filters: $(config.filterSelector),
      $loadMoreCont: $(config.loadMoreContSelector),
      $loadMore: $(config.loadMoreSelector),
    });

    queryDomFilters();
    queryDomPosts();

    $(document).on("click", config.loadMoreContSelector, onLoadMoreClick);
    $(document).on("change", config.filterSelector, onFilterChange);

    window.addEventListener("popstate", onPopState);
  }

  function queryDomFilters() {
    if (!state.$filters) {
      return;
    }

    const filters: Filter[] = state.$filters
      .filter((_, el) => el.checked)
      .map((_, { name, value }) => ({
        taxonomy: name,
        term: value,
      }))
      .get();

    setState({ filters });
  }

  function queryDomPosts() {
    const $items = $(config.itemSelector);

    setState({
      $items,
      count: $items.length,
    });
  }

  function setRouteState() {
    const { filters } = state;
    window.history.replaceState({ filters }, "");
  }

  function setRoute() {
    const parts = [window.PTH.homeUrl, window.PTH.postTypeSlug];
    const q = buildQueryFromFilters(state.filters);

    if (q) {
      parts.push("q", q);
    }

    const next = parts.filter(Boolean).join("/") + "/";
    window.history.pushState(null, "", next);
  }

  function onLoadMoreClick(e: JQuery.ClickEvent) {
    e.preventDefault();

    setState({ page: state.page + 1 });
    getPosts();
  }

  function onFilterChange(e: JQuery.ChangeEvent) {
    const { name, value } = e.target;

    if (!name || !value) {
      return;
    }

    if (state.getPostsDebounce) {
      state.getPostsDebounce.clear();
    }

    setRouteState();
    queryDomFilters();

    const getPostsDebounce = debounce(getPostsFresh, 1);

    getPostsDebounce();
    setState({ page: 1, getPostsDebounce });
    setRoute();
  }

  function onPopState(event: PopStateEvent) {
    const newState = event.state as Partial<State>;
    setState(newState);
    getPostsFresh();
  }

  function getPostsFresh() {
    return getPosts({ empty: true });
  }

  async function getPosts(params: Partial<GetPostsParams> = {}): Promise<void> {
    const defaults: GetPostsParams = {
      empty: false,
      placeItems: true,
    };

    const { placeItems, empty } = { ...defaults, ...params };

    const q = buildQueryFromFilters(state.filters);

    const requestParams: { [key: string]: string } = {
      action: "get-posts",
      page: String(state.page),
      type: window.PTH.postType,
      q,
    };

    const url = new URL(window.PTH.ajaxUrl);

    for (const paramKey of Object.keys(requestParams)) {
      url.searchParams.append(paramKey, requestParams[paramKey]);
    }

    if (state.getPostsAbort) {
      state.getPostsAbort.abort();
    }

    const abortController = new AbortController();

    setState({
      getPostsAbort: abortController,
    });

    try {
      const newState: Partial<State> = {};
      const result = await window
        .fetch(url.toString(), { signal: abortController.signal })
        .then((d) => d.json().then((data) => data as GetPostsPayload));

      if (state.$filtersContainer && result.filtersContainer) {
        const $filtersContainer = $(result.filtersContainer.markup);
        state.$filtersContainer.replaceWith($filtersContainer);
        newState.$filtersContainer = $filtersContainer;
        newState.$filters = newState.$filtersContainer.find<HTMLInputElement>(
          config.filterSelector
        );
      }

      if (state.$hero && result.hero) {
        const $hero = $(result.hero.markup);
        state.$hero.replaceWith($hero);
        newState.$hero = $hero;
      }

      if (state.$heroBottom && result.heroBottom) {
        const $heroBottom = $(result.heroBottom.markup);
        state.$heroBottom.replaceWith($heroBottom);
        newState.$heroBottom = $heroBottom;
      }

      if (state.$cont && state.$page) {
        if (empty) {
          state.$cont.empty();
        }

        if (placeItems) {
          const $pageClone = state.$page.clone().empty();
          for (const item of result.items) {
            if (!item.markup) {
              continue;
            }

            const $elem = $(item.markup);
            $elem.appendTo($pageClone);
          }

          state.$cont.append($pageClone);

          if (result.cta) {
            $(result.cta.markup).appendTo(state.$cont);
          }

          if (state.$loadMoreCont) {
            state.$cont.append(state.$loadMoreCont);
          }

          queryDomPosts();
        }
      }

      setState({
        ...newState,
        total: result.total,
        totalPages: result.totalPages,
        getPostsAbort: undefined,
      });
    } catch (err) {}
  }

  init();

  getPosts({
    placeItems: false,
  });
});
