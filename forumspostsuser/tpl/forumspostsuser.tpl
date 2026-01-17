/**
 * forumspostsuser.tpl - Plugin Users latest posts. Frontend Page latest posts of User in Forums
 *
 * forumspostsuser plugin for Cotonti 0.9.26, PHP 8.4+
 * Filename: forumspostsuser.tpl
 *
 * Date: Jan 16Th, 2026
 * @package forumspostsuser
 * @version 2.0.1
 * @author webitproff
 * @copyright Copyright (c) webitproff 2026 | https://github.com/webitproff
 * @license BSD
 */
<!-- BEGIN: MAIN -->
<div class="border-bottom border-secondary py-3 px-3">
    <nav aria-label="breadcrumb">
        <div class="ps-container-breadcrumb">
            <ol class="breadcrumb d-flex mb-0">
                <li class="breadcrumb-item">
                    <a href="{PHP.cfg.mainurl}">{PHP.L.Home}</a>
				</li>
                <li class="breadcrumb-item">
                    <a href="{PHP|cot_url('forums')}" title="{PHP.L.Forums}">
                  <span class="me-2">
                    <i class="fa-solid fa-comments me-1"></i>
                  </span>{PHP.L.Forums} </a>
				</li>
                <li class="breadcrumb-item active" aria-current="page">
                    {PHP.L.forumspostsuser_title}
				</li>
			</ol>
		</div>
	</nav>
</div>
<div class="min-vh-50 px-2 px-md-3 py-4">
	<div class="col-12  px-1 px-md-3"> 
	<h3 class="h4 mb-3">{PHP.L.forumspostsuser_title_my}</h3>
    <!-- BEGIN: POSTS -->
    <div class="list-group list-group-flush">
        <!-- BEGIN: TOPIC -->
        <div class="list-group-item list-group-item-action">
            <a href="{FORUMSPOSTSUSER_FORUMS_POST_URL}" class="text-decoration-none">
                <h5 class="mb-1 fs-6 fw-bold text-primary">
                    <span class="text-muted small"></span>{FORUMSPOSTSUSER_FORUMS_TITLE}
                </h5>
                <p class="mb-1 text-muted small">{FORUMSPOSTSUSER_FORUMS_TEXT}</p>
            </a>
            <!-- IF {FORUMSPOSTSUSER_CATEGORY_SHORT} -->
            <p class="mb-0 small text-secondary">{FORUMSPOSTSUSER_DATE}: {FORUMSPOSTSUSER_CATEGORY_SHORT}</p>
            <!-- ENDIF -->
        </div>
        <!-- END: TOPIC -->
    </div>
    <!-- END: POSTS -->
    <!-- IF {PAGINATION} -->
    <nav aria-label="Posts pagination" class="mt-3">
        <ul class="pagination justify-content-center">
			{PREVIOUS_PAGE} {PAGINATION} {NEXT_PAGE}
        </ul>
    </nav>
	<p class="mb-0 small text-secondary">{PHP.L.Total}: {TOTAL_ENTRIES} {PHP.L.Onpage}: {ENTRIES_ON_CURRENT_PAGE}</p>
    <!-- ENDIF -->
    <!-- IF {FORUMSPOSTSUSER_COUNT} == 0 -->
    <div class="alert alert-light" role="alert">
        {PHP.L.None}
    </div>
    <!-- ENDIF -->
</div>
</div>
<!-- END: MAIN -->