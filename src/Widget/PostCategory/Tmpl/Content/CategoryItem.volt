{% set children = item.getRelated('children') %}
{% set itemClass = 'category-item' ~ item.id ~ ' uk-parent' %}
{% if strpos(currentUri(), item.route) === 0 %}
    {% set itemClass = itemClass ~ ' uk-active' %}
{% endif %}

{% set itemContent = '<a href="' ~ route(item.route) ~ '"' ~ '>' ~ item.title ~ '</a>' %}

{% if children.count() %}
    {% set itemContent = itemContent ~ renderer.getPartial('Content/CategorySub', ['items': children]) %}
{% endif %}

{% if widget['params.showPosts'] === 'Y' %}
    {% set posts = instance.getPosts(item) %}
    {% if posts | length %}
        {% set itemContent = itemContent ~ renderer.getPartial('Content/PostSub', ['posts': posts]) %}
    {% endif %}
{% endif %}

<li class="{{ itemClass }}">
    {{ itemContent }}
</li>