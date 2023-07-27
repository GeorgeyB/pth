interface PostTypeHelperProps {
    ajaxUrl: string;
    postType: string;
    postTypeSlug: string;
    homeUrl: string;
}

declare global {
    interface Window {
        PTH: PostTypeHelperProps;
    }
}

export interface Filter {
    taxonomy: string;
    term: string;
}

export interface State {
    count: number;
    page: number;
    total: number;
    totalPages: number;
    filters: Filter[];
    $cont?: JQuery;
    $hero?: JQuery;
    $heroBottom?: JQuery;
    $page?: JQuery;
    $items?: JQuery;
    $filtersContainer?: JQuery<HTMLElement>;
    $filters?: JQuery<HTMLInputElement>;
    $loadMoreCont?: JQuery;
    $loadMore?: JQuery;
    getPostsDebounce?: (() => Promise<void>) & { clear(): void; flush(): void };
    getPostsAbort?: AbortController;
}

export interface PostPayload {
    id: number;
    markup: string;
}

export interface FiltersContainerPayload {
    markup: string;
}

export interface HeroPayload {
    markup: string;
}

export interface HeroBottomPayload {
    markup: string;
}

export interface CtaPayload {
    markup: string;
}

export interface GetPostsPayload {
    filtersContainer?: FiltersContainerPayload;
    items: PostPayload[];
    hero?: HeroPayload;
    heroBottom?: HeroBottomPayload;
    cta?: CtaPayload;
    total: number;
    totalPages: number;
}

export interface GetPostsParams {
    empty: boolean;
    placeItems: boolean;
}
