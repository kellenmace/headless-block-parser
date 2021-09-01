# Headless Block Parser

Plugin for headless WordPress projects that use the [WPGraphQL Gutenberg](https://github.com/pristas-peter/wp-graphql-gutenberg) extension. It implements a custom block parser that replaces internal link URL domains with that of the decoupled frontend JS app.

With this plugin in place, a link inside of a blog post's content that points to `https://my-wp-backend.local/blog/hello-world` will be re-written to instead point to `http:localhost:3000/blog/hello-world`, for example.

## Steps to Use

1. Clone down this repo into your project's `/plugins` directory.
1. Modify the `$frontend_app_url = 'http://localhost:3000';` line so that it gets the frontend app URL from an environment variable or the database - wherever you store it. This ensures that the internal link URLs will be re-written properly in all environments (development/staging/production).
1. Install and activate the plugin.
1. Test a few internal links in your decoupled frontend JS app to ensure their domains have been re-written properly.

The `data-internal-link="true"` data attribute that this plugin adds to internal links can also be used to convert regular anchor tags to your JS framework's `Link` component, as described in this blog post:
https://developers.wpengine.com/blog/gutenberg-in-headless-wordpress-render-blocks-as-html/
