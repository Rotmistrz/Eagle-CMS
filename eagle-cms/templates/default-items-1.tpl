<ul>
	{% for item in itemsCollection %}
		<li>
			<h1>{{ item.header_1 }}</h1>
			<p>
			{{ item.content_1 }}
			</p>
		</li>
	{% endfor %}
</ul>