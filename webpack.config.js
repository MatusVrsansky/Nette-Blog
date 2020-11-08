const webpack = require('webpack');
const path = require('path');
const TerserJSPlugin = require('terser-webpack-plugin');
const MiniCssExtractPlugin = require('mini-css-extract-plugin');
const OptimizeCSSAssetsPlugin = require('optimize-css-assets-webpack-plugin');

const config = {
	entry: './www/js/main.js',
	optimization: {
		minimizer: [new TerserJSPlugin({}), new OptimizeCSSAssetsPlugin({
			cssProcessorOptions: {
				safe: true,
				discardComments: {
					removeAll: true,
				},
			},
		})],
	},
	output: {
		path: path.resolve(__dirname, './www/dist'),
		filename: 'bundle.js'
	},
	module: {
		rules: [
			{
				test: /\.(png|jpg|svg)$/,
				loader: 'file-loader',
				options: {
					name: '[name].[ext]',
					outputPath: 'images/'
				}
			},
			{
				test: /\.(woff2|woff)$/,
				loader: 'file-loader',
				options: {
					name: '[name].[ext]',
					outputPath: 'fonts/'
				}
			},
			{
				test: /\.css$/,
				use: [
					MiniCssExtractPlugin.loader,
					'css-loader'
				]
			},
			{
				test: /\.less$/,
				use: [
					MiniCssExtractPlugin.loader,
					'css-loader',
					'less-loader'
				]
			}
		]
	},
	plugins: [
		new MiniCssExtractPlugin()
	]
};

module.exports = config;