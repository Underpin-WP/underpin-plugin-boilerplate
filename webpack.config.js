/**
 * Webpack Config.
 *
 * This configuration is a fork of the config that comes with the block editor scripts.
 */

/**
 * External dependencies
 */
const { BundleAnalyzerPlugin } = require( 'webpack-bundle-analyzer' );
const LiveReloadPlugin = require( 'webpack-livereload-plugin' );
const path = require( 'path' );
const MiniCssExtractPlugin = require( 'mini-css-extract-plugin' );
const globImporter = require( 'node-sass-glob-importer' );

/**
 * WordPress dependencies
 */
const DependencyExtractionWebpackPlugin = require( '@wordpress/dependency-extraction-webpack-plugin' );

/**
 * Internal dependencies
 */
const { hasBabelConfig } = require( './node_modules/@wordpress/scripts/utils' );

const isProduction = process.env.NODE_ENV === 'production';
const mode = isProduction ? 'production' : 'development';

const config = {
	mode,
	entry: {
		// index: path.resolve( process.cwd(), 'src', 'index.js' ),
		// admin: path.resolve(process.cwd(), 'src', 'admin/admin.js'),
		// blocks: path.resolve( process.cwd(), 'src', 'blocks.js' ),
	},
	output: {
		filename: '[name].js',
		path: path.resolve( process.cwd(), 'build' ),
	},
	resolve: {
		alias: {
			'lodash-es': 'lodash',
		},
	},
	module: {
		rules: [

			{
				test: /admin\/.*.js$/,
				exclude: /node_modules/,
				loader: 'babel-loader',
				query: {
					"presets": ["@babel/preset-react"]
				}
			},

			// Compile all Block Editor blocks using the build configuration that comes with WordPress
			{
				test: /.(js|jsx)$/,
				exclude: /node_modules/,
				use: [
					require.resolve( 'thread-loader' ),
					{
						loader: require.resolve( 'babel-loader' ),
						options: {
							// Babel uses a directory within local node_modules
							// by default. Use the environment variable option
							// to enable more persistent caching.
							cacheDirectory: process.env.BABEL_CACHE_DIRECTORY || true,

							// Provide a fallback configuration if there's not
							// one explicitly available in the project.
							...( !hasBabelConfig() && {
								babelrc: false,
								configFile: false,
								presets: [require.resolve( '@wordpress/babel-preset-default' )],
							} ),
						},
					},
				],
			},

			{
				test: /\.s[ac]ss$|\.css$/i,
				use: [
					{
						loader: MiniCssExtractPlugin.loader,
						options: {
							hmr: process.env.NODE_ENV === 'development',
						},
					},
					// Translates CSS into CommonJS
					'css-loader',
					// Compiles Sass to CSS
					{
						loader: 'sass-loader',
						options: {
							importer: globImporter()
						}
					}
				],
			},
		],
	},
	plugins: [
		// process.env.WP_BUNDLE_ANALYZER && new BundleAnalyzerPlugin(),
		// !isProduction && new LiveReloadPlugin( { port: process.env.WP_LIVE_RELOAD_PORT || 35729 } ),
		// new DependencyExtractionWebpackPlugin( { injectPolyfill: true } ),
		new MiniCssExtractPlugin( {
			filename: '[name].css',
		} ),
	].filter( Boolean ),
	stats: {
		children: false,
	},
};

if( !isProduction ){
	// WP_DEVTOOL global variable controls how source maps are generated.
	// See: https://webpack.js.org/configuration/devtool/#devtool.
	config.devtool = process.env.WP_DEVTOOL || 'source-map';
	config.module.rules.unshift( {
		test: /\.js$/,
		use: require.resolve( 'source-map-loader' ),
		enforce: 'pre',
	} );
}

module.exports = config;
