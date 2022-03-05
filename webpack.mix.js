const mix = require("laravel-mix");



mix.config.webpackConfig = {
  output: {
    publicPath: "/vendor/deep-admin/",
  }
};


mix
  .js("./resources/js/app.js", "public")
  .extract(["axios", "vue", "vuex", "vue-router", "element-ui"])
  .setResourceRoot("/vendor/deep-admin")
  .setPublicPath("public")
    //deep admin start
    .copy("public", "../../../public/vendor/deep-admin")
    //deep admin end
  .webpackConfig({
    resolve: {
      alias: {
        "@": path.resolve(__dirname, "resources/js/"),
      },
    },
  })
  .options({
    extractVueStyles: false,
    processCssUrls: false,
  })
  .disableNotifications().version();
