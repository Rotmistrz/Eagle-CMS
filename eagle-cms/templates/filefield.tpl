<section class="filefield">
    <div class="filefield__current">
        {% if file.isPicture %}
            <img src="{{ file.path }}?time={{ time }}" alt="" />
        {% else %}
            <a href="{{ file.path }}" target="_blank">Zobacz plik</a>
        {% endif %}
    </div>

    <input id="{{ id }}" name="{{ id }}" type="file" />
</section>