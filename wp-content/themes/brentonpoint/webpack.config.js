const path                = require('path');
const MiniCssExtractPlugin = require('mini-css-extract-plugin');
const CssMinimizerPlugin   = require('css-minimizer-webpack-plugin');
const TerserPlugin         = require('terser-webpack-plugin');

const isProd = process.env.NODE_ENV === 'production';

module.exports = {
  mode: isProd ? 'production' : 'development',
  devtool: isProd ? false : 'source-map',

  entry: {
    main: './src/js/main.js',
  },

  output: {
    path:     path.resolve(__dirname, 'dist/js'),
    filename: '[name].js',
    clean:    true,
  },

  module: {
    rules: [
      {
        test:    /\.js$/,
        exclude: /node_modules/,
        use:     { loader: 'babel-loader' },
      },
      {
        test: /\.(scss|css)$/,
        use: [
          MiniCssExtractPlugin.loader,
          { loader: 'css-loader', options: { sourceMap: !isProd } },
          { loader: 'sass-loader', options: { sourceMap: !isProd, api: 'modern' } },
        ],
      },
    ],
  },

  plugins: [
    new MiniCssExtractPlugin({
      filename: '../css/[name].css',
    }),
  ],

  optimization: {
    minimizer: [
      new TerserPlugin(),
      new CssMinimizerPlugin(),
    ],
  },
};
