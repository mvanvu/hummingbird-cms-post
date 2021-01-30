<ul class="uk-nav-sub uk-nav-parent-icon" uk-nav="multiple: true">
    {% for item in items %}
        {{ renderer.getPartial('Content/CategoryItem', ['item': item]) }}
    {% endfor %}
</ul>