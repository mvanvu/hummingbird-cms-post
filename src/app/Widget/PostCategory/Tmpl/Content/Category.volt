<ul class="uk-nav-default uk-nav">
    {% for item in category.getRelated('children') %}
        {{ renderer.getPartial('Content/CategoryItem', ['item': item]) }}
    {% endfor %}
</ul>