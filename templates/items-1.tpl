		{% for item in itemsCollection %}
		
        <article class="article">
            <img class="article__picture" src="/uploads/1/{{ item.id }}.jpg" alt="" />
            <h1 class="article__title">{{ item.header_1 }}</h1>
            <section class="article__content">
        		<p>
        		{{ item.content_1 }}
        		</p>
            </section>
        </article>

		{% endfor %}