/**
 * WordPress Dependencies
 */
const defaultConfig = require( '@wordpress/scripts/config/webpack.config.js' );

defaultConfig.output.chunkLoadingGlobal = defaultConfig.jsonpFunction

delete defaultConfig.output.jsonpFunction

module.exports = {
        ...defaultConfig,
        ...{
                // Add any overrides to the default here.
        }
}
