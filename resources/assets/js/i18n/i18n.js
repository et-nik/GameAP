// import { createApp } from 'vue';
import plurals from './plurals';
import _ from 'lodash';

const pluralForms = {
    default: (n) => (n !== 1 ? 1 : 0),
    en: (n) => (n !== 1 ? 1 : 0),
    ru: (n) =>
        n % 10 === 1 && n % 100 !== 11
            ? 0
            : n % 10 >= 2 && n % 10 <= 4 && (n % 100 < 10 || n % 100 >= 20)
                ? 1
                : 2,
};

const pluralize = (choice, choicesLength) => {
    let lang = document.documentElement.lang;

    if (!plurals.hasOwnProperty(lang)) {
        lang = 'default';
    }

    if (!plurals[lang].hasOwnProperty(choice)) {
        return choice;
    }

    const index = pluralForms[lang](choicesLength);

    return plurals[lang][choice][index] === undefined
        ? plurals[lang][choice][0]
        : plurals[lang][choice][index];
};

const trans = (string, args) => {
    let value = _.get(window.i18n, string);

    _.eachRight(args, (paramVal, paramKey) => {
        value = _.replace(value, `:${paramKey}`, paramVal);
    });
    return value;
};

let lang = null;
const pageLanguage = () => {
    if (lang) {
        return lang;
    }

    const htmlEl = document.getElementsByTagName('html')
    if (htmlEl.length === 0) {
        lang = navigator.language ?? 'en'
        return lang
    }

    const langAttribute = htmlEl[0].getAttribute('lang')

    if (langAttribute === null) {
        lang = navigator.language ?? 'en'
        return lang
    }

    lang = langAttribute ?? 'en'
    return lang
}

export {pluralize, trans, pageLanguage}
