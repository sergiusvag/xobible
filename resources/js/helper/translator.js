let translation;
let translate;
const enTranslate = (string) => {
    return string;
};
const OtherTranslate = (string) => {
    return translation[string] ? translation[string] : string;
};

export const initLang = async (locale) => {
    if (locale === "en") {
        translate = enTranslate;
    } else {
        translate = OtherTranslate;
        await fetch(`/lang/${locale}.json`)
            .then((response) => response.json())
            .then((json) => {
                translation = json;
            });
    }
};

export const __ = (string) => {
    return translate(string);
};
