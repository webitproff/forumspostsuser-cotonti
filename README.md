# forumspostsuser-cotonti
This plugin displays user messages posted on the forum in two formats: a separate page with a list of their own messages and a separate tab in the user's profile with a list of their posts. Plugin for Cotonti 0.9.26, PHP 8.4+

# Forum Posts User

Plugin for Cotonti CMS that displays forum posts authored by a user, both as a standalone page for the current user and as a tab in the user profile.

---

## Description

**Forum Posts User** extends the standard Cotonti forums and users modules by adding functionality to list forum posts created by a specific user.

The plugin provides:

- a standalone page showing **posts of the currently authorized user**;
- a **profile tab** in `users.details` showing posts of any user (subject to access permissions);
- pagination, post text truncation, and permission-aware output.

The plugin does not introduce new database tables and relies entirely on existing forum structures.

---

## Requirements

- Cotonti Siena `0.9.26+`
- PHP `8.4+`
- Active **forums** module
- Standard Cotonti users module

---

## Installation

1. Copy the plugin directory `forumspostsuser` into:


plugins/forumspostsuser/

2. Install the plugin via the Cotonti administration panel:

Administration → Extensions → Forum Posts User → Install

3. Ensure the **forums** module is installed and enabled.

---

## Plugin Structure


```
forumspostsuser/
├── forumspostsuser.php
├── forumspostsuser.global.php
├── forumspostsuser.functions.php
├── forumspostsuser.users.details.php
├── forumspostsuser.setup.php
├── lang/
├── forumspostsuser.en.lang.php
├── forumspostsuser.ru.lang.php
├── tpl/
├── forumspostsuser.tpl
└── forumspostsuser.users.details.tpl
```

---

## Configuration

Available plugin configuration options:

- **showpostsinlist**  
  Number of posts displayed per page.

- **postscutinlist**  
  Maximum number of characters shown per post (after stripping markup).

Configuration is available via:


Administration → Extensions → Forum Posts User → Configuration


---

## Frontend Usage

### Standalone page (authorized user only)

The plugin exposes a standalone page accessible via:

```
cot_url('plug', 'e=forumspostsuser')
```

Access rules:

user must be authorized;

only the current user's posts are shown;

posts are sorted by last update date (DESC).

Pagination is handled using Cotonti standard pagination helpers.

Menu integration example

Example of adding a menu item for authorized users:
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

User Profile Integration

The plugin hooks into:

users.details.tags


and provides the following template tags:

{FORUMSPOSTSUSER_TAB}

{USERS_DETAILS_FORUMSPOSTSUSER_URL}

{USERS_DETAILS_FORUMSPOSTSUSER_COUNT}

Tab link example
```
<!-- IF {PHP|cot_plugin_active('forumspostsuser')} -->
<li class="nav-item">
	<a class="nav-link<!-- IF {PHP.tab} == 'forumspostsuser' --> active<!-- ENDIF -->" href="{USERS_DETAILS_FORUMSPOSTSUSER_URL}#tab_forumspostsuser" data-bs-toggle="tab" role="tab">
		{PHP.L.forumspostsuser_title} ({USERS_DETAILS_FORUMSPOSTSUSER_COUNT})
	</a>
</li>
<!-- ENDIF -->
```

Tab content example
```
<!-- IF {PHP|cot_plugin_active('forumspostsuser')} -->
<div class="tab-pane fade<!-- IF {PHP.tab} == 'forumspostsuser' --> show active<!-- ENDIF -->" id="tab_forumspostsuser" role="tabpanel">
	{FORUMSPOSTSUSER_TAB}
</div>
<!-- ENDIF -->
```

Server-Side Logic Overview
Data sources

The plugin works directly with:

forum posts table

forum topics table

forum structure

No additional tables are created.

Core logic

determines target user ID (current user or profile owner);

verifies user existence;

checks forum read permissions per post category;

counts total posts for pagination;

fetches posts with LIMIT and OFFSET;

parses post text using forum markup settings;

strips HTML and truncates content;

builds post URLs pointing directly to forum posts.

Permissions Handling

Forum read permissions are checked using:

cot_auth('forums', $category, 'R')


Posts from inaccessible categories are excluded.

If the forums module is disabled, the plugin does nothing.

If the requested user does not exist, profile tab output is disabled.

Templates
forumspostsuser.tpl

Used for the standalone page showing the current user’s posts.

Features:

breadcrumb navigation;

paginated list of posts;

topic title, post excerpt, date, category;

empty state message if no posts exist.

forumspostsuser.users.details.tpl

Used for the profile tab.

Features:

embedded output inside user profile;

pagination support;
access-aware post listing;
post count display.

Localization

Language strings are loaded via:

cot_langfile('forumspostsuser', 'plug')


All user-facing text is localized.

Multilingual environments are supported out of the box.


Hooks Used

 - global
 - standalone
 - users.details.tags


Limitations

Only forum posts are supported.

No filtering by category or topic.

No caching layer.

No moderation or edit actions included.

Compatibility

Uses only Cotonti core APIs.

Compatible with standard forum markup settings.

Safe for custom themes (template-based output).

License BSD License

Author: webitproff https://github.com/webitproff
