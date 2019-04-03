const { join } = require('path');

// define the current context (root)
const context = process.cwd();

// define the default theme
const defaultTheme = 'default';

function getAppSettingsByStore(store) {
    // define the applicatin name
    const name = `${store.name}_${store.theme}`;

    // define relative urls to site host (/)
    const urls = {
        // assets base url
        assets: join('/assets', store.name, store.theme)
    };

    // define project relative paths to context
    const paths = {
        // locate the typescript configuration json file
        tsConfig: './tsconfig.json',

        // assets folder
        assets: join('./frontend', urls.assets),

        // public folder
        public: join('./public/Yves', urls.assets),

        // core folders
        core: {
            // all modules
            modules: './vendor/spryker-shop',
            // ShopUi source folder
            shopUiModule: `./vendor/spryker-shop/shop-ui/src/SprykerShop/Yves/ShopUi/Theme/${defaultTheme}`
        },

        // eco folders
        eco: {
            // all modules
            modules: './vendor/spryker-eco'
        },

        // project folders
        project: {
            // all modules
            modules: './src/Pyz/Yves',
            // ShopUi source folder
            shopUiModule: `./src/Pyz/Yves/ShopUi${store.name}/Theme/${store.theme}`
        }
    };

    const getStorePatterns = () => {
        if (store.isDefault) {
            return []
        }

        const currentThemeIndex = store
            .themesFallbackChain
            .indexOf(store.theme);

        return store
            .themesFallbackChain
            .slice(currentThemeIndex)
            .reverse()
            .map(theme => [
                `**/*${store.name}/Theme/${theme}/components/atoms/*/index.ts`,
                `**/*${store.name}/Theme/${theme}/components/molecules/*/index.ts`,
                `**/*${store.name}/Theme/${theme}/components/organisms/*/index.ts`,
                `**/*${store.name}/Theme/${theme}/templates/*/index.ts`,
                `**/*${store.name}/Theme/${theme}/views/*/index.ts`
            ])
            .reduce((patterns, themePatterns) => patterns.concat(themePatterns), []);
    }

    // return settings
    return {
        name,
        store,
        context,
        paths,
        urls,

        // define settings for suite-frontend-builder finder
        find: {
            // webpack entry points (components) finder settings
            componentEntryPoints: {
                // absolute dirs in which look for
                dirs: [
                    join(context, paths.core.modules),
                    join(context, paths.eco.modules),
                    join(context, paths.project.modules)
                ],
                // files/dirs patterns
                patterns: [
                    `**/Theme/${defaultTheme}/components/atoms/*/index.ts`,
                    `**/Theme/${defaultTheme}/components/molecules/*/index.ts`,
                    `**/Theme/${defaultTheme}/components/organisms/*/index.ts`,
                    `**/Theme/${defaultTheme}/templates/*/index.ts`,
                    `**/Theme/${defaultTheme}/views/*/index.ts`,
                    ...getStorePatterns(),
                    '!config',
                    '!data',
                    '!deploy',
                    '!node_modules',
                    '!public',
                    '!test'
                ]
            },

            // core component styles finder settings
            // important: this part is used in shared scss environment
            // do not change unless necessary
            componentStyles: {
                // absolute dirs in which look for
                dirs: [
                    join(context, paths.core.modules)
                ],
                // files/dirs patterns
                patterns: [
                    `**/Theme/${defaultTheme}/components/atoms/*/*.scss`,
                    `**/Theme/${defaultTheme}/components/molecules/*/*.scss`,
                    `**/Theme/${defaultTheme}/components/organisms/*/*.scss`,
                    `**/Theme/${defaultTheme}/templates/*/*.scss`,
                    `**/Theme/${defaultTheme}/views/*/*.scss`,
                    `!**/Theme/${defaultTheme}/**/style.scss`,
                    '!config',
                    '!data',
                    '!deploy',
                    '!node_modules',
                    '!public',
                    '!test'
                ]
            }
        }
    }
}

module.exports = {
    getAppSettingsByStore
}
