# Boilerplate for Custom Projects

This is a plugin boilerplate built on the [Underpin](https://github.com/alexstandiford/underpin) Framework. For
information on how to use this, check out Underpin's docs.

This plugin expects that Underpin has been installed as a [WordPress Must-Use plugin](https://wordpress.org/support/article/must-use-plugins/).

## Webpack Config

The Webpack and NPM configuration in this plugin is a barebones WordPress configuration that aligns the script dir with
Underpin's default script directory. It is intentionally un-opinionated, but it is set-up and ready to be extended.

The default entrypoint is `src/index.js`.