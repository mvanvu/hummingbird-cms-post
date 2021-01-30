<ul class="uk-nav-sub">
    {% for post in posts %}
        <li class="post-item{{ post.id }}{{ currentUri() == post.route ? ' uk-active' : '' }}">
            <a href="{{ route(post.route) }}">
                {{ post.title }}
            </a>
        </li>
    {% endfor %}
</ul>