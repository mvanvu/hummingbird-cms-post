<section class="uk-section uk-section-small">
    <div class="uk-container">
        <div class="uk-text-center">
            {{ partial('Breadcrumb/Breadcrumb') }}
            <h1 class="uk-heading-line"><span>{{ postCategory.t('title') }}</span></h1>
        </div>
        {% set params = postCategory.getParams() %}
        {{ helper('Widget::createWidget',
            'Post',
            [
                'displayLayout': 'BlogList',
                'categoryIds': [postCategory.id | intval],
                'listLimit': params.get('listLimit', 15),
                'sortBy': params.get('sortBy', 'latest')
            ],
            true,
            'Raw'
        ) }}
    </div>
</section>