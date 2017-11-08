		{% for item in itemsCollection %}
		
        <article class="article">
            <img class="article__picture" src="/uploads/items/1/{{ item.id }}.jpg" alt="" />
            <h1 class="article__title">{{ item.header_1 }} {@ _phone_01_ @}</h1>
            <section class="article__content">
        		<p>
        		{{ item.content_1 }}
        		</p>

                <p>
                {{ item.content_2 }}
                </p>
            </section>
        </article>
            {% for gallery in item.gallery %}
                <img src="/uploads/galleries/{{ gallery.filename.square }}" alt="" />
            {% endfor %}

		{% endfor %}