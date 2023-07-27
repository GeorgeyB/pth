const { WebpackManifestPlugin } = require('webpack-manifest-plugin');
const path = require('path');

const mode = process.env.NODE_ENV === 'development' ? 'development' : 'production';

module.exports = {
    mode,
    entry: {
        'post-type-archive': './src/post-type-archive.ts'
    },
    module: {
        rules: [{
                test: /\.m?js$/,
                exclude: /(node_modules|bower_components)/,
                use: {
                    loader: 'babel-loader',
                    options: {
                        presets: ['@babel/preset-env']
                    }
                }
            },
            {
                test: /\.tsx?$/,
                use: 'ts-loader',
                exclude: /node_modules/
            }
        ]
    },
    plugins: [new WebpackManifestPlugin()],
    resolve: {
        extensions: ['.tsx', '.ts', '.js']
    },
    output: {
        filename: '[name].[contenthash].js',
        publicPath: '',
        path: path.resolve(__dirname, 'dist'),
        clean: true
    }
};