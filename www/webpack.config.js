import Encore from '@symfony/webpack-encore';

Encore
     .setOutputPath('public/build/')
     .setPublicPath('/build')
     .addEntry('app', './assets/js/app.js')
     .enableVueLoader()
     .enableSingleRuntimeChunk()
     .cleanupOutputBeforeBuild()
     .enableBuildNotifications()
     .enableSourceMaps(!Encore.isProduction())
     .enableVersioning(Encore.isProduction())
     .configureBabel(() => {}, {
         useBuiltIns: 'usage',
         corejs: 3
     })
     //.enableSassLoader()
     //.enableTypeScriptLoader()
     //.enableIntegrityHashes()
     //.autoProvidejQuery()
     //.enableReactPreset()
     //.enableVueLoader()
;

export default Encore.getWebpackConfig();