<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta content="IE=edge,chrome=1" http-equiv="X-UA-Compatible">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <title>Jos Metro BUS API Documentation</title>

    <link href="https://fonts.googleapis.com/css?family=Open+Sans&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset("/vendor/scribe/css/theme-default.style.css") }}" media="screen">
    <link rel="stylesheet" href="{{ asset("/vendor/scribe/css/theme-default.print.css") }}" media="print">

    <script src="https://cdn.jsdelivr.net/npm/lodash@4.17.10/lodash.min.js"></script>

    <link rel="stylesheet"
          href="https://unpkg.com/@highlightjs/cdn-assets@11.6.0/styles/obsidian.min.css">
    <script src="https://unpkg.com/@highlightjs/cdn-assets@11.6.0/highlight.min.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jets/0.14.1/jets.min.js"></script>

    <style id="language-style">
        /* starts out as display none and is replaced with js later  */
                    body .content .bash-example code { display: none; }
                    body .content .javascript-example code { display: none; }
            </style>

    <script>
        var tryItOutBaseUrl = "http://localhost";
        var useCsrf = Boolean();
        var csrfUrl = "/sanctum/csrf-cookie";
    </script>
    <script src="{{ asset("/vendor/scribe/js/tryitout-5.3.0.js") }}"></script>

    <script src="{{ asset("/vendor/scribe/js/theme-default-5.3.0.js") }}"></script>

</head>

<body data-languages="[&quot;bash&quot;,&quot;javascript&quot;]">

<a href="#" id="nav-button">
    <span>
        MENU
        <img src="{{ asset("/vendor/scribe/images/navbar.png") }}" alt="navbar-image"/>
    </span>
</a>
<div class="tocify-wrapper">
    
            <div class="lang-selector">
                                            <button type="button" class="lang-button" data-language-name="bash">bash</button>
                                            <button type="button" class="lang-button" data-language-name="javascript">javascript</button>
                    </div>
    
    <div class="search">
        <input type="text" class="search" id="input-search" placeholder="Search">
    </div>

    <div id="toc">
                    <ul id="tocify-header-introduction" class="tocify-header">
                <li class="tocify-item level-1" data-unique="introduction">
                    <a href="#introduction">Introduction</a>
                </li>
                            </ul>
                    <ul id="tocify-header-authenticating-requests" class="tocify-header">
                <li class="tocify-item level-1" data-unique="authenticating-requests">
                    <a href="#authenticating-requests">Authenticating requests</a>
                </li>
                            </ul>
                    <ul id="tocify-header-endpoints" class="tocify-header">
                <li class="tocify-item level-1" data-unique="endpoints">
                    <a href="#endpoints">Endpoints</a>
                </li>
                                    <ul id="tocify-subheader-endpoints" class="tocify-subheader">
                                                    <li class="tocify-item level-2" data-unique="endpoints-GETapi-user">
                                <a href="#endpoints-GETapi-user">GET api/user</a>
                            </li>
                                                                        </ul>
                            </ul>
                    <ul id="tocify-header-notifications" class="tocify-header">
                <li class="tocify-item level-1" data-unique="notifications">
                    <a href="#notifications">Notifications</a>
                </li>
                                    <ul id="tocify-subheader-notifications" class="tocify-subheader">
                                                    <li class="tocify-item level-2" data-unique="notifications-POSTapi-v1-notifications-test">
                                <a href="#notifications-POSTapi-v1-notifications-test">Send a Test Notification</a>
                            </li>
                                                                        </ul>
                            </ul>
                    <ul id="tocify-header-rewards-points-system" class="tocify-header">
                <li class="tocify-item level-1" data-unique="rewards-points-system">
                    <a href="#rewards-points-system">Rewards & Points System</a>
                </li>
                                    <ul id="tocify-subheader-rewards-points-system" class="tocify-subheader">
                                                    <li class="tocify-item level-2" data-unique="rewards-points-system-GETapi-v1-rewards">
                                <a href="#rewards-points-system-GETapi-v1-rewards">Get total reward points.</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="rewards-points-system-GETapi-v1-rewards-history">
                                <a href="#rewards-points-system-GETapi-v1-rewards-history">Get reward transaction history.</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="rewards-points-system-POSTapi-v1-rewards">
                                <a href="#rewards-points-system-POSTapi-v1-rewards">Award points for user actions.</a>
                            </li>
                                                                        </ul>
                            </ul>
                    <ul id="tocify-header-routes-management" class="tocify-header">
                <li class="tocify-item level-1" data-unique="routes-management">
                    <a href="#routes-management">Routes Management</a>
                </li>
                                    <ul id="tocify-subheader-routes-management" class="tocify-subheader">
                                                    <li class="tocify-item level-2" data-unique="routes-management-GETapi-v1-routes-export">
                                <a href="#routes-management-GETapi-v1-routes-export">Export all static routes in JSON format for mobile app usage.</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="routes-management-GETapi-v1-routes">
                                <a href="#routes-management-GETapi-v1-routes">Display a listing of all available routes.</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="routes-management-GETapi-v1-routes--id-">
                                <a href="#routes-management-GETapi-v1-routes--id-">Display a specific route by ID.</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="routes-management-POSTapi-v1-routes">
                                <a href="#routes-management-POSTapi-v1-routes">Store a newly created route (Admin only).</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="routes-management-PUTapi-v1-routes--route_id-">
                                <a href="#routes-management-PUTapi-v1-routes--route_id-">Update an existing route (Admin only).</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="routes-management-DELETEapi-v1-routes--route_id-">
                                <a href="#routes-management-DELETEapi-v1-routes--route_id-">Delete a route (Admin only).</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="routes-management-POSTapi-v1-login">
                                <a href="#routes-management-POSTapi-v1-login">Authenticate a user (testing endpoint).</a>
                            </li>
                                                                        </ul>
                            </ul>
            </div>

    <ul class="toc-footer" id="toc-footer">
                    <li style="padding-bottom: 5px;"><a href="{{ route("scribe.postman") }}">View Postman collection</a></li>
                            <li style="padding-bottom: 5px;"><a href="{{ route("scribe.openapi") }}">View OpenAPI spec</a></li>
                <li><a href="http://github.com/knuckleswtf/scribe">Documentation powered by Scribe ✍</a></li>
    </ul>

    <ul class="toc-footer" id="last-updated">
        <li>Last updated: October 23, 2025</li>
    </ul>
</div>

<div class="page-wrapper">
    <div class="dark-box"></div>
    <div class="content">
        <h1 id="introduction">Introduction</h1>
<aside>
    <strong>Base URL</strong>: <code>http://localhost</code>
</aside>
<pre><code>This documentation aims to provide all the information you need to work with our API.

&lt;aside&gt;As you scroll, you'll see code examples for working with the API in different programming languages in the dark area to the right (or as part of the content on mobile).
You can switch the language used with the tabs at the top right (or from the nav menu at the top left on mobile).&lt;/aside&gt;</code></pre>

        <h1 id="authenticating-requests">Authenticating requests</h1>
<p>This API is not authenticated.</p>

        <h1 id="endpoints">Endpoints</h1>

    

                                <h2 id="endpoints-GETapi-user">GET api/user</h2>

<p>
</p>



<span id="example-requests-GETapi-user">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "http://localhost/api/user" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost/api/user"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-GETapi-user">
            <blockquote>
            <p>Example response (401):</p>
        </blockquote>
                <details class="annotation">
            <summary style="cursor: pointer;">
                <small onclick="textContent = parentElement.parentElement.open ? 'Show headers' : 'Hide headers'">Show headers</small>
            </summary>
            <pre><code class="language-http">cache-control: no-cache, private
content-type: application/json
access-control-allow-origin: *
access-control-allow-methods: GET, POST, PUT, PATCH, DELETE, OPTIONS
access-control-allow-headers: Content-Type, Authorization, X-Requested-With
access-control-allow-credentials: true
 </code></pre></details>         <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;Unauthenticated.&quot;
}</code>
 </pre>
    </span>
<span id="execution-results-GETapi-user" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-user"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-user"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-user" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-user">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-user" data-method="GET"
      data-path="api/user"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-user', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-user"
                    onclick="tryItOut('GETapi-user');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-user"
                    onclick="cancelTryOut('GETapi-user');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-user"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/user</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-user"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="GETapi-user"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        </form>

                <h1 id="notifications">Notifications</h1>

    <p>APIs for managing push notifications</p>

                                <h2 id="notifications-POSTapi-v1-notifications-test">Send a Test Notification</h2>

<p>
</p>

<p>This endpoint sends a push notification to a device using Firebase Cloud Messaging (FCM).</p>

<span id="example-requests-POSTapi-v1-notifications-test">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request POST \
    "http://localhost/api/v1/notifications/test" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json" \
    --data "{
    \"token\": \"\\\"dYV1S2A4-abc123xyz\\\"\",
    \"title\": \"\\\"Reward Earned\\\"\",
    \"body\": \"\\\"You received 50 bonus points!\\\"\",
    \"type\": \"\\\"reward\\\"\"
}"
</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost/api/v1/notifications/test"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "token": "\"dYV1S2A4-abc123xyz\"",
    "title": "\"Reward Earned\"",
    "body": "\"You received 50 bonus points!\"",
    "type": "\"reward\""
};

fetch(url, {
    method: "POST",
    headers,
    body: JSON.stringify(body),
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-POSTapi-v1-notifications-test">
            <blockquote>
            <p>Example response (200):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;success&quot;: true,
    &quot;message&quot;: &quot;Notification sent successfully!&quot;,
    &quot;response&quot;: {
        &quot;name&quot;: &quot;projects/.../messages/12345&quot;
    }
}</code>
 </pre>
            <blockquote>
            <p>Example response (422):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;success&quot;: false,
    &quot;message&quot;: &quot;Validation failed&quot;,
    &quot;errors&quot;: {
        &quot;token&quot;: [
            &quot;The token field is required.&quot;
        ]
    }
}</code>
 </pre>
            <blockquote>
            <p>Example response (500):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;success&quot;: false,
    &quot;message&quot;: &quot;Failed to send notification.&quot;,
    &quot;error&quot;: &quot;Server error message&quot;
}</code>
 </pre>
    </span>
<span id="execution-results-POSTapi-v1-notifications-test" hidden>
    <blockquote>Received response<span
                id="execution-response-status-POSTapi-v1-notifications-test"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-v1-notifications-test"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-POSTapi-v1-notifications-test" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-v1-notifications-test">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-POSTapi-v1-notifications-test" data-method="POST"
      data-path="api/v1/notifications/test"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('POSTapi-v1-notifications-test', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-POSTapi-v1-notifications-test"
                    onclick="tryItOut('POSTapi-v1-notifications-test');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-POSTapi-v1-notifications-test"
                    onclick="cancelTryOut('POSTapi-v1-notifications-test');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-POSTapi-v1-notifications-test"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-black">POST</small>
            <b><code>api/v1/notifications/test</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="POSTapi-v1-notifications-test"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="POSTapi-v1-notifications-test"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
        <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>token</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="token"                data-endpoint="POSTapi-v1-notifications-test"
               value=""dYV1S2A4-abc123xyz""
               data-component="body">
    <br>
<p>The FCM device token. Example: <code>"dYV1S2A4-abc123xyz"</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>title</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="title"                data-endpoint="POSTapi-v1-notifications-test"
               value=""Reward Earned""
               data-component="body">
    <br>
<p>The title of the notification. Example: <code>"Reward Earned"</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>body</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="body"                data-endpoint="POSTapi-v1-notifications-test"
               value=""You received 50 bonus points!""
               data-component="body">
    <br>
<p>The notification message body. Example: <code>"You received 50 bonus points!"</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>type</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="text" style="display: none"
                              name="type"                data-endpoint="POSTapi-v1-notifications-test"
               value=""reward""
               data-component="body">
    <br>
<p>optional The type of notification. Must be one of: reward, route_update, admin_alert. Example: <code>"reward"</code></p>
        </div>
        </form>

                <h1 id="rewards-points-system">Rewards & Points System</h1>

    <p>APIs for retrieving and managing user reward points and reward history.</p>
<p>These endpoints handle how users earn and view their Tin City Metro rewards.</p>
<p>Base URL: <code>/api/rewards</code></p>

                                <h2 id="rewards-points-system-GETapi-v1-rewards">Get total reward points.</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>

<p>Retrieve the user’s total accumulated reward points.</p>

<span id="example-requests-GETapi-v1-rewards">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "http://localhost/api/v1/rewards" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost/api/v1/rewards"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-GETapi-v1-rewards">
            <blockquote>
            <p>Example response (200, Success):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;data&quot;: {
        &quot;user_id&quot;: 1,
        &quot;total_points&quot;: 15,
        &quot;description&quot;: &quot;Total reward points&quot;
    },
    &quot;message&quot;: &quot;Reward balance retrieved successfully&quot;
}</code>
 </pre>
            <blockquote>
            <p>Example response (200, No rewards):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;data&quot;: null,
    &quot;message&quot;: &quot;No rewards yet&quot;
}</code>
 </pre>
    </span>
<span id="execution-results-GETapi-v1-rewards" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-v1-rewards"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-v1-rewards"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-v1-rewards" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-v1-rewards">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-v1-rewards" data-method="GET"
      data-path="api/v1/rewards"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-v1-rewards', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-v1-rewards"
                    onclick="tryItOut('GETapi-v1-rewards');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-v1-rewards"
                    onclick="cancelTryOut('GETapi-v1-rewards');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-v1-rewards"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/v1/rewards</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-v1-rewards"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="GETapi-v1-rewards"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        </form>

                    <h2 id="rewards-points-system-GETapi-v1-rewards-history">Get reward transaction history.</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>

<p>Retrieve a paginated list of all actions that earned the user points.</p>

<span id="example-requests-GETapi-v1-rewards-history">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "http://localhost/api/v1/rewards/history?page=1" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost/api/v1/rewards/history"
);

const params = {
    "page": "1",
};
Object.keys(params)
    .forEach(key =&gt; url.searchParams.append(key, params[key]));

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-GETapi-v1-rewards-history">
            <blockquote>
            <p>Example response (200, Success):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;data&quot;: [
        {
            &quot;id&quot;: 1,
            &quot;user_id&quot;: 1,
            &quot;action&quot;: &quot;crowd_report&quot;,
            &quot;points&quot;: 3,
            &quot;description&quot;: &quot;Reported bus crowding&quot;,
            &quot;created_at&quot;: &quot;2025-10-23T10:00:00Z&quot;
        },
        {
            &quot;id&quot;: 2,
            &quot;user_id&quot;: 1,
            &quot;action&quot;: &quot;share_location&quot;,
            &quot;points&quot;: 4,
            &quot;description&quot;: &quot;Shared location data&quot;,
            &quot;created_at&quot;: &quot;2025-10-23T11:00:00Z&quot;
        }
    ],
    &quot;message&quot;: &quot;Reward history retrieved successfully&quot;
}</code>
 </pre>
            <blockquote>
            <p>Example response (200, No history):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;data&quot;: [],
    &quot;message&quot;: &quot;No reward history found&quot;
}</code>
 </pre>
    </span>
<span id="execution-results-GETapi-v1-rewards-history" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-v1-rewards-history"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-v1-rewards-history"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-v1-rewards-history" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-v1-rewards-history">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-v1-rewards-history" data-method="GET"
      data-path="api/v1/rewards/history"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-v1-rewards-history', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-v1-rewards-history"
                    onclick="tryItOut('GETapi-v1-rewards-history');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-v1-rewards-history"
                    onclick="cancelTryOut('GETapi-v1-rewards-history');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-v1-rewards-history"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/v1/rewards/history</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-v1-rewards-history"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="GETapi-v1-rewards-history"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                            <h4 class="fancy-heading-panel"><b>Query Parameters</b></h4>
                                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>page</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="number" style="display: none"
               step="any"               name="page"                data-endpoint="GETapi-v1-rewards-history"
               value="1"
               data-component="query">
    <br>
<p>optional The page number for pagination. Example: <code>1</code></p>
            </div>
                </form>

                    <h2 id="rewards-points-system-POSTapi-v1-rewards">Award points for user actions.</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>

<p>Award points to the authenticated user for performing specific actions like sharing location or reporting crowd.</p>

<span id="example-requests-POSTapi-v1-rewards">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request POST \
    "http://localhost/api/v1/rewards" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json" \
    --data "{
    \"action\": \"location_share\",
    \"description\": \"Shared my bus location\",
    \"prevent_duplicate\": false
}"
</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost/api/v1/rewards"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "action": "location_share",
    "description": "Shared my bus location",
    "prevent_duplicate": false
};

fetch(url, {
    method: "POST",
    headers,
    body: JSON.stringify(body),
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-POSTapi-v1-rewards">
            <blockquote>
            <p>Example response (200, Success):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;Points awarded successfully&quot;,
    &quot;points&quot;: 3
}</code>
 </pre>
            <blockquote>
            <p>Example response (400, Invalid action):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;Invalid action or no points awarded&quot;
}</code>
 </pre>
            <blockquote>
            <p>Example response (422, Validation error):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;The action field is required.&quot;
}</code>
 </pre>
    </span>
<span id="execution-results-POSTapi-v1-rewards" hidden>
    <blockquote>Received response<span
                id="execution-response-status-POSTapi-v1-rewards"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-v1-rewards"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-POSTapi-v1-rewards" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-v1-rewards">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-POSTapi-v1-rewards" data-method="POST"
      data-path="api/v1/rewards"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('POSTapi-v1-rewards', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-POSTapi-v1-rewards"
                    onclick="tryItOut('POSTapi-v1-rewards');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-POSTapi-v1-rewards"
                    onclick="cancelTryOut('POSTapi-v1-rewards');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-POSTapi-v1-rewards"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-black">POST</small>
            <b><code>api/v1/rewards</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="POSTapi-v1-rewards"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="POSTapi-v1-rewards"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
        <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>action</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="action"                data-endpoint="POSTapi-v1-rewards"
               value="location_share"
               data-component="body">
    <br>
<p>The action that earned points. Example: <code>location_share</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>description</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="text" style="display: none"
                              name="description"                data-endpoint="POSTapi-v1-rewards"
               value="Shared my bus location"
               data-component="body">
    <br>
<p>optional Description of the action. Example: <code>Shared my bus location</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>prevent_duplicate</code></b>&nbsp;&nbsp;
<small>boolean</small>&nbsp;
<i>optional</i> &nbsp;
                <label data-endpoint="POSTapi-v1-rewards" style="display: none">
            <input type="radio" name="prevent_duplicate"
                   value="true"
                   data-endpoint="POSTapi-v1-rewards"
                   data-component="body"             >
            <code>true</code>
        </label>
        <label data-endpoint="POSTapi-v1-rewards" style="display: none">
            <input type="radio" name="prevent_duplicate"
                   value="false"
                   data-endpoint="POSTapi-v1-rewards"
                   data-component="body"             >
            <code>false</code>
        </label>
    <br>
<p>optional Whether to prevent duplicate rewards. Default: false Example: <code>false</code></p>
        </div>
        </form>

                <h1 id="routes-management">Routes Management</h1>

    <p>APIs for managing and retrieving bus routes within the Jos Metro BOSS system.</p>
<p>These endpoints handle both public and admin operations for static routes.</p>
<p>Base URL: <code>/api/v1/routes</code></p>

                                <h2 id="routes-management-GETapi-v1-routes-export">Export all static routes in JSON format for mobile app usage.</h2>

<p>
</p>



<span id="example-requests-GETapi-v1-routes-export">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "http://localhost/api/v1/routes/export" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost/api/v1/routes/export"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-GETapi-v1-routes-export">
            <blockquote>
            <p>Example response (200, Success):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
  &quot;exported_at&quot;: &quot;2025-10-18T15:00:00Z&quot;,
  &quot;routes&quot;: [...]
}</code>
 </pre>
    </span>
<span id="execution-results-GETapi-v1-routes-export" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-v1-routes-export"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-v1-routes-export"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-v1-routes-export" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-v1-routes-export">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-v1-routes-export" data-method="GET"
      data-path="api/v1/routes/export"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-v1-routes-export', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-v1-routes-export"
                    onclick="tryItOut('GETapi-v1-routes-export');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-v1-routes-export"
                    onclick="cancelTryOut('GETapi-v1-routes-export');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-v1-routes-export"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/v1/routes/export</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-v1-routes-export"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="GETapi-v1-routes-export"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        </form>

                    <h2 id="routes-management-GETapi-v1-routes">Display a listing of all available routes.</h2>

<p>
</p>



<span id="example-requests-GETapi-v1-routes">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "http://localhost/api/v1/routes" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost/api/v1/routes"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-GETapi-v1-routes">
            <blockquote>
            <p>Example response (200, Success):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;data&quot;: [
        {
            &quot;id&quot;: 1,
            &quot;name&quot;: &quot;Terminus to Bukuru&quot;,
            &quot;encoded_polyline&quot;: &quot;mfp_Ijk~hE...&quot;,
            &quot;distance&quot;: 8.3,
            &quot;created_at&quot;: &quot;2025-10-18T12:00:00Z&quot;
        }
    ]
}</code>
 </pre>
    </span>
<span id="execution-results-GETapi-v1-routes" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-v1-routes"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-v1-routes"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-v1-routes" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-v1-routes">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-v1-routes" data-method="GET"
      data-path="api/v1/routes"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-v1-routes', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-v1-routes"
                    onclick="tryItOut('GETapi-v1-routes');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-v1-routes"
                    onclick="cancelTryOut('GETapi-v1-routes');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-v1-routes"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/v1/routes</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-v1-routes"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="GETapi-v1-routes"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        </form>

                    <h2 id="routes-management-GETapi-v1-routes--id-">Display a specific route by ID.</h2>

<p>
</p>



<span id="example-requests-GETapi-v1-routes--id-">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "http://localhost/api/v1/routes/1" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost/api/v1/routes/1"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-GETapi-v1-routes--id-">
            <blockquote>
            <p>Example response (200, Success):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;data&quot;: {
        &quot;id&quot;: 1,
        &quot;name&quot;: &quot;Terminus to Bukuru&quot;,
        &quot;encoded_polyline&quot;: &quot;mfp_Ijk~hE...&quot;,
        &quot;distance&quot;: 8.3,
        &quot;created_at&quot;: &quot;2025-10-18T12:00:00Z&quot;
    }
}</code>
 </pre>
            <blockquote>
            <p>Example response (404, Not Found):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;error&quot;: &quot;Route not found&quot;
}</code>
 </pre>
    </span>
<span id="execution-results-GETapi-v1-routes--id-" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-v1-routes--id-"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-v1-routes--id-"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-v1-routes--id-" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-v1-routes--id-">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-v1-routes--id-" data-method="GET"
      data-path="api/v1/routes/{id}"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-v1-routes--id-', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-v1-routes--id-"
                    onclick="tryItOut('GETapi-v1-routes--id-');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-v1-routes--id-"
                    onclick="cancelTryOut('GETapi-v1-routes--id-');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-v1-routes--id-"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/v1/routes/{id}</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-v1-routes--id-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="GETapi-v1-routes--id-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="id"                data-endpoint="GETapi-v1-routes--id-"
               value="1"
               data-component="url">
    <br>
<p>The ID of the route. Example: <code>1</code></p>
            </div>
                    </form>

                    <h2 id="routes-management-POSTapi-v1-routes">Store a newly created route (Admin only).</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>



<span id="example-requests-POSTapi-v1-routes">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request POST \
    "http://localhost/api/v1/routes" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json" \
    --data "{
    \"name\": \"Terminus to Bukuru\",
    \"start_point\": \"n\",
    \"end_point\": \"g\",
    \"encoded_polyline\": \"mfp_Ijk~hEo}@yDa@e...\",
    \"distance_km\": 39,
    \"stops\": [
        \"architecto\"
    ],
    \"distance\": 8.3
}"
</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost/api/v1/routes"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "name": "Terminus to Bukuru",
    "start_point": "n",
    "end_point": "g",
    "encoded_polyline": "mfp_Ijk~hEo}@yDa@e...",
    "distance_km": 39,
    "stops": [
        "architecto"
    ],
    "distance": 8.3
};

fetch(url, {
    method: "POST",
    headers,
    body: JSON.stringify(body),
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-POSTapi-v1-routes">
            <blockquote>
            <p>Example response (201, Created):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;data&quot;: {
        &quot;id&quot;: 1,
        &quot;name&quot;: &quot;Terminus to Bukuru&quot;,
        &quot;encoded_polyline&quot;: &quot;mfp_Ijk~hE...&quot;,
        &quot;distance&quot;: 8.3,
        &quot;created_at&quot;: &quot;2025-10-18T12:00:00Z&quot;
    }
}</code>
 </pre>
    </span>
<span id="execution-results-POSTapi-v1-routes" hidden>
    <blockquote>Received response<span
                id="execution-response-status-POSTapi-v1-routes"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-v1-routes"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-POSTapi-v1-routes" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-v1-routes">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-POSTapi-v1-routes" data-method="POST"
      data-path="api/v1/routes"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('POSTapi-v1-routes', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-POSTapi-v1-routes"
                    onclick="tryItOut('POSTapi-v1-routes');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-POSTapi-v1-routes"
                    onclick="cancelTryOut('POSTapi-v1-routes');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-POSTapi-v1-routes"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-black">POST</small>
            <b><code>api/v1/routes</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="POSTapi-v1-routes"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="POSTapi-v1-routes"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
        <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>name</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="name"                data-endpoint="POSTapi-v1-routes"
               value="Terminus to Bukuru"
               data-component="body">
    <br>
<p>The name of the route. Example: <code>Terminus to Bukuru</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>start_point</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="start_point"                data-endpoint="POSTapi-v1-routes"
               value="n"
               data-component="body">
    <br>
<p>Must not be greater than 255 characters. Example: <code>n</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>end_point</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="end_point"                data-endpoint="POSTapi-v1-routes"
               value="g"
               data-component="body">
    <br>
<p>Must not be greater than 255 characters. Example: <code>g</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>encoded_polyline</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="encoded_polyline"                data-endpoint="POSTapi-v1-routes"
               value="mfp_Ijk~hEo}@yDa@e..."
               data-component="body">
    <br>
<p>Google Maps encoded polyline representing the route path. Example: <code>mfp_Ijk~hEo}@yDa@e...</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>distance_km</code></b>&nbsp;&nbsp;
<small>number</small>&nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="distance_km"                data-endpoint="POSTapi-v1-routes"
               value="39"
               data-component="body">
    <br>
<p>Must be at least 0. Example: <code>39</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
        <details>
            <summary style="padding-bottom: 10px;">
                <b style="line-height: 2;"><code>stops</code></b>&nbsp;&nbsp;
<small>string[]</small>&nbsp;
<i>optional</i> &nbsp;
<br>
<p>Optional list of stops related to the route.</p>
            </summary>
                                                <div style="margin-left: 14px; clear: unset;">
                        <b style="line-height: 2;"><code>name</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="text" style="display: none"
                              name="stops.0.name"                data-endpoint="POSTapi-v1-routes"
               value="architecto"
               data-component="body">
    <br>
<p>This field is required when <code>stops</code> is present. Example: <code>architecto</code></p>
                    </div>
                                                                <div style="margin-left: 14px; clear: unset;">
                        <b style="line-height: 2;"><code>latitude</code></b>&nbsp;&nbsp;
<small>number</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="number" style="display: none"
               step="any"               name="stops.0.latitude"                data-endpoint="POSTapi-v1-routes"
               value="4326.41688"
               data-component="body">
    <br>
<p>This field is required when <code>stops</code> is present. Example: <code>4326.41688</code></p>
                    </div>
                                                                <div style="margin-left: 14px; clear: unset;">
                        <b style="line-height: 2;"><code>longitude</code></b>&nbsp;&nbsp;
<small>number</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="number" style="display: none"
               step="any"               name="stops.0.longitude"                data-endpoint="POSTapi-v1-routes"
               value="4326.41688"
               data-component="body">
    <br>
<p>This field is required when <code>stops</code> is present. Example: <code>4326.41688</code></p>
                    </div>
                                                                <div style="margin-left: 14px; clear: unset;">
                        <b style="line-height: 2;"><code>order_index</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="number" style="display: none"
               step="any"               name="stops.0.order_index"                data-endpoint="POSTapi-v1-routes"
               value="16"
               data-component="body">
    <br>
<p>This field is required when <code>stops</code> is present. Example: <code>16</code></p>
                    </div>
                                    </details>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>distance</code></b>&nbsp;&nbsp;
<small>number</small>&nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="distance"                data-endpoint="POSTapi-v1-routes"
               value="8.3"
               data-component="body">
    <br>
<p>The total distance (in km). Example: <code>8.3</code></p>
        </div>
        </form>

                    <h2 id="routes-management-PUTapi-v1-routes--route_id-">Update an existing route (Admin only).</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>



<span id="example-requests-PUTapi-v1-routes--route_id-">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request PUT \
    "http://localhost/api/v1/routes/16" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json" \
    --data "{
    \"name\": \"architecto\",
    \"start_point\": \"n\",
    \"end_point\": \"g\",
    \"encoded_polyline\": \"architecto\",
    \"distance_km\": 39,
    \"stops\": [
        {
            \"name\": \"architecto\",
            \"latitude\": 4326.41688,
            \"longitude\": 4326.41688,
            \"order_index\": 16
        }
    ],
    \"distance\": 4326.41688
}"
</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost/api/v1/routes/16"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "name": "architecto",
    "start_point": "n",
    "end_point": "g",
    "encoded_polyline": "architecto",
    "distance_km": 39,
    "stops": [
        {
            "name": "architecto",
            "latitude": 4326.41688,
            "longitude": 4326.41688,
            "order_index": 16
        }
    ],
    "distance": 4326.41688
};

fetch(url, {
    method: "PUT",
    headers,
    body: JSON.stringify(body),
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-PUTapi-v1-routes--route_id-">
            <blockquote>
            <p>Example response (200, Updated):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;data&quot;: {
        &quot;id&quot;: 1,
        &quot;name&quot;: &quot;Terminus to Bukuru (Updated)&quot;,
        &quot;encoded_polyline&quot;: &quot;xyz123...&quot;,
        &quot;distance&quot;: 9.1
    }
}</code>
 </pre>
            <blockquote>
            <p>Example response (403, Forbidden):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;error&quot;: &quot;You are not authorized to perform this action&quot;
}</code>
 </pre>
    </span>
<span id="execution-results-PUTapi-v1-routes--route_id-" hidden>
    <blockquote>Received response<span
                id="execution-response-status-PUTapi-v1-routes--route_id-"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-PUTapi-v1-routes--route_id-"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-PUTapi-v1-routes--route_id-" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-PUTapi-v1-routes--route_id-">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-PUTapi-v1-routes--route_id-" data-method="PUT"
      data-path="api/v1/routes/{route_id}"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('PUTapi-v1-routes--route_id-', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-PUTapi-v1-routes--route_id-"
                    onclick="tryItOut('PUTapi-v1-routes--route_id-');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-PUTapi-v1-routes--route_id-"
                    onclick="cancelTryOut('PUTapi-v1-routes--route_id-');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-PUTapi-v1-routes--route_id-"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-darkblue">PUT</small>
            <b><code>api/v1/routes/{route_id}</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="PUTapi-v1-routes--route_id-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="PUTapi-v1-routes--route_id-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>route_id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="route_id"                data-endpoint="PUTapi-v1-routes--route_id-"
               value="16"
               data-component="url">
    <br>
<p>The ID of the route. Example: <code>16</code></p>
            </div>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>route</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="route"                data-endpoint="PUTapi-v1-routes--route_id-"
               value="1"
               data-component="url">
    <br>
<p>The ID of the route to update. Example: <code>1</code></p>
            </div>
                            <h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
        <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>name</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="text" style="display: none"
                              name="name"                data-endpoint="PUTapi-v1-routes--route_id-"
               value="architecto"
               data-component="body">
    <br>
<p>optional The updated name of the route. Example: <code>architecto</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>start_point</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="text" style="display: none"
                              name="start_point"                data-endpoint="PUTapi-v1-routes--route_id-"
               value="n"
               data-component="body">
    <br>
<p>Must not be greater than 255 characters. Example: <code>n</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>end_point</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="text" style="display: none"
                              name="end_point"                data-endpoint="PUTapi-v1-routes--route_id-"
               value="g"
               data-component="body">
    <br>
<p>Must not be greater than 255 characters. Example: <code>g</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>encoded_polyline</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="text" style="display: none"
                              name="encoded_polyline"                data-endpoint="PUTapi-v1-routes--route_id-"
               value="architecto"
               data-component="body">
    <br>
<p>optional Updated Google Maps polyline. Example: <code>architecto</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>distance_km</code></b>&nbsp;&nbsp;
<small>number</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="number" style="display: none"
               step="any"               name="distance_km"                data-endpoint="PUTapi-v1-routes--route_id-"
               value="39"
               data-component="body">
    <br>
<p>Must be at least 0. Example: <code>39</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
        <details>
            <summary style="padding-bottom: 10px;">
                <b style="line-height: 2;"><code>stops</code></b>&nbsp;&nbsp;
<small>object[]</small>&nbsp;
<i>optional</i> &nbsp;
<br>

            </summary>
                                                <div style="margin-left: 14px; clear: unset;">
                        <b style="line-height: 2;"><code>name</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="text" style="display: none"
                              name="stops.0.name"                data-endpoint="PUTapi-v1-routes--route_id-"
               value="architecto"
               data-component="body">
    <br>
<p>This field is required when <code>stops</code> is present. Example: <code>architecto</code></p>
                    </div>
                                                                <div style="margin-left: 14px; clear: unset;">
                        <b style="line-height: 2;"><code>latitude</code></b>&nbsp;&nbsp;
<small>number</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="number" style="display: none"
               step="any"               name="stops.0.latitude"                data-endpoint="PUTapi-v1-routes--route_id-"
               value="4326.41688"
               data-component="body">
    <br>
<p>This field is required when <code>stops</code> is present. Example: <code>4326.41688</code></p>
                    </div>
                                                                <div style="margin-left: 14px; clear: unset;">
                        <b style="line-height: 2;"><code>longitude</code></b>&nbsp;&nbsp;
<small>number</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="number" style="display: none"
               step="any"               name="stops.0.longitude"                data-endpoint="PUTapi-v1-routes--route_id-"
               value="4326.41688"
               data-component="body">
    <br>
<p>This field is required when <code>stops</code> is present. Example: <code>4326.41688</code></p>
                    </div>
                                                                <div style="margin-left: 14px; clear: unset;">
                        <b style="line-height: 2;"><code>order_index</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="number" style="display: none"
               step="any"               name="stops.0.order_index"                data-endpoint="PUTapi-v1-routes--route_id-"
               value="16"
               data-component="body">
    <br>
<p>This field is required when <code>stops</code> is present. Example: <code>16</code></p>
                    </div>
                                    </details>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>distance</code></b>&nbsp;&nbsp;
<small>number</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="number" style="display: none"
               step="any"               name="distance"                data-endpoint="PUTapi-v1-routes--route_id-"
               value="4326.41688"
               data-component="body">
    <br>
<p>optional Updated distance (in km). Example: <code>4326.41688</code></p>
        </div>
        </form>

                    <h2 id="routes-management-DELETEapi-v1-routes--route_id-">Delete a route (Admin only).</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>



<span id="example-requests-DELETEapi-v1-routes--route_id-">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request DELETE \
    "http://localhost/api/v1/routes/16" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost/api/v1/routes/16"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "DELETE",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-DELETEapi-v1-routes--route_id-">
            <blockquote>
            <p>Example response (200, Deleted):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;Route deleted successfully&quot;
}</code>
 </pre>
            <blockquote>
            <p>Example response (404, Not Found):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;error&quot;: &quot;Route not found&quot;
}</code>
 </pre>
    </span>
<span id="execution-results-DELETEapi-v1-routes--route_id-" hidden>
    <blockquote>Received response<span
                id="execution-response-status-DELETEapi-v1-routes--route_id-"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-DELETEapi-v1-routes--route_id-"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-DELETEapi-v1-routes--route_id-" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-DELETEapi-v1-routes--route_id-">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-DELETEapi-v1-routes--route_id-" data-method="DELETE"
      data-path="api/v1/routes/{route_id}"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('DELETEapi-v1-routes--route_id-', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-DELETEapi-v1-routes--route_id-"
                    onclick="tryItOut('DELETEapi-v1-routes--route_id-');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-DELETEapi-v1-routes--route_id-"
                    onclick="cancelTryOut('DELETEapi-v1-routes--route_id-');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-DELETEapi-v1-routes--route_id-"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-red">DELETE</small>
            <b><code>api/v1/routes/{route_id}</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="DELETEapi-v1-routes--route_id-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="DELETEapi-v1-routes--route_id-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>route_id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="route_id"                data-endpoint="DELETEapi-v1-routes--route_id-"
               value="16"
               data-component="url">
    <br>
<p>The ID of the route. Example: <code>16</code></p>
            </div>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>route</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="route"                data-endpoint="DELETEapi-v1-routes--route_id-"
               value="1"
               data-component="url">
    <br>
<p>The ID of the route to delete. Example: <code>1</code></p>
            </div>
                    </form>

                    <h2 id="routes-management-POSTapi-v1-login">Authenticate a user (testing endpoint).</h2>

<p>
</p>



<span id="example-requests-POSTapi-v1-login">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request POST \
    "http://localhost/api/v1/login" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json" \
    --data "{
    \"email\": \"test@example.com\",
    \"password\": \"password\"
}"
</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost/api/v1/login"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "email": "test@example.com",
    "password": "password"
};

fetch(url, {
    method: "POST",
    headers,
    body: JSON.stringify(body),
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-POSTapi-v1-login">
            <blockquote>
            <p>Example response (200, Success):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;user&quot;: {
        &quot;id&quot;: 1,
        &quot;name&quot;: &quot;Test Admin&quot;,
        &quot;email&quot;: &quot;test@example.com&quot;,
        &quot;role&quot;: &quot;admin&quot;
    },
    &quot;token&quot;: &quot;1|XyzABC123...&quot;
}</code>
 </pre>
            <blockquote>
            <p>Example response (401, Unauthorized):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;Invalid credentials&quot;
}</code>
 </pre>
    </span>
<span id="execution-results-POSTapi-v1-login" hidden>
    <blockquote>Received response<span
                id="execution-response-status-POSTapi-v1-login"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-v1-login"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-POSTapi-v1-login" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-v1-login">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-POSTapi-v1-login" data-method="POST"
      data-path="api/v1/login"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('POSTapi-v1-login', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-POSTapi-v1-login"
                    onclick="tryItOut('POSTapi-v1-login');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-POSTapi-v1-login"
                    onclick="cancelTryOut('POSTapi-v1-login');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-POSTapi-v1-login"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-black">POST</small>
            <b><code>api/v1/login</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="POSTapi-v1-login"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="POSTapi-v1-login"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
        <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>email</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="email"                data-endpoint="POSTapi-v1-login"
               value="test@example.com"
               data-component="body">
    <br>
<p>The user's email address. Example: <code>test@example.com</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>password</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="password"                data-endpoint="POSTapi-v1-login"
               value="password"
               data-component="body">
    <br>
<p>The user's password. Example: <code>password</code></p>
        </div>
        </form>

            

        
    </div>
    <div class="dark-box">
                    <div class="lang-selector">
                                                        <button type="button" class="lang-button" data-language-name="bash">bash</button>
                                                        <button type="button" class="lang-button" data-language-name="javascript">javascript</button>
                            </div>
            </div>
</div>
</body>
</html>
