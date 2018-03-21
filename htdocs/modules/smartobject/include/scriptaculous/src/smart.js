Effect.Combo = function (element) {
    element = $(element);
    if (element.style.display == 'none') {
        new Effect.Appear(element, arguments[1] || {});
    } else {
        new Effect.Fade(element, arguments[1] || {});
    }
}
