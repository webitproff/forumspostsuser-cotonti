куда-то в меню авторизованного пользователя
```
<!-- IF {PHP|cot_plugin_active('forumspostsuser')} -->
<li class="nav-item">
	<a href="{PHP|cot_url('plug', 'e=forumspostsuser')}" class="nav-link" title="{PHP.L.forumspostsuser_title_my}">
	<span class="me-2"><i class="fa-solid fa-comments fa-lg"></i></span>
	<span>{PHP.L.forumspostsuser_title_my}</span>
	</a>
</li>
<!-- ENDIF -->
```


ссылка на вкладку
```
<!-- IF {PHP|cot_plugin_active('forumspostsuser')} -->
<li class="nav-item">
	<a class="nav-link<!-- IF {PHP.tab} == 'forumspostsuser' --> active<!-- ENDIF -->" href="{USERS_DETAILS_FORUMSPOSTSUSER_URL}#tab_forumspostsuser" data-bs-toggle="tab" role="tab">
		{PHP.L.forumspostsuser_title} ({USERS_DETAILS_FORUMSPOSTSUSER_COUNT})
	</a>
</li>
<!-- ENDIF -->
```


содержание вкладки
```
<!-- IF {PHP|cot_plugin_active('forumspostsuser')} -->
<div class="tab-pane fade<!-- IF {PHP.tab} == 'forumspostsuser' --> show active<!-- ENDIF -->" id="tab_forumspostsuser" role="tabpanel">
	{FORUMSPOSTSUSER_TAB}
</div>
<!-- ENDIF -->
```