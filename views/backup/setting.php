<?php
$download_link = get_option('siteurl').substr($path, -41);
$link1 = $this->router(array('controller'=>'backup', 'function'=>'backupAllData'));
$link2 = $this->router(array('controller'=>'backup', 'function'=>'checkingChangeFile'));
$link3 = $this->router(array('controller'=>'backup', 'function'=>'deleteBackup'));
?>
<div class="wrap">
    <div class="icon32" id="icon-generic">
        <br/>
    </div>
    <h2>Upwards Technologies Backup</h2>

    <div>
        <ul class="subsubsub">
            <li>
                <a href="<?php echo $link1; ?>">Back up All data</a>
                |
            </li>
            <li>
                <a href="<?php echo $link2; ?>">Check Change File</a>
            </li>
        </ul>

        <table class="widefat">
            <thead>
            <tr>
                <th>Name</th>
                <th>Date</th>
                <th>Size</th>
                <th>Details</th>
                <th>Actions</th>
            </tr>
            </thead>
            <tbody>

            <?php if(!empty($list_files)): ?>
                <?php foreach($list_files as $list): ?>

                    <?php
                    $filename = $list->filename;
                    $download = $download_link.rawurlencode($filename);

                    $type = filesize($path.$filename);
                    $date = $list->date;

                    if($type / 1073741824 >= 1)
                    {
                        $type = $type / 1073741824;
                        $type = number_format($type, 2) . ' TB';
                    }
                    elseif($type / 1048576 >= 1)
                    {
                        $type = $type / 1048576;
                        $type = number_format($type, 2) . ' MB';
                    }
                    elseif($type / 1024 >= 1)
                    {
                        $type = $type / 1024;
                        $type = number_format($type, 2) . ' KB';
                    }
                    else
                    {
                        $type = number_format($type, 2) . ' B';
                    }

                    $data = $list->data;
                    if( (is_object($data) || is_array($data)) AND !empty($data) )
                    {
                        $temp = '';
                        $first = true;
                        foreach($data as $file)
                        {
                            if($first)
                            {
                                $first = false;
                                $temp .= 'ROOT'.$file->parent.DS.$file->name;
                            }
                            else
                            {
                                $temp .= '<br/>ROOT'.$file->parent.DS.$file->name;
                            }

                        }
                        $data = $temp;
                    }
                    else
                    {
                        if(strlen($data) <= 0)
                            $data = 'Cannot found any detail';
                    }

                    ?>

                <tr>
                    <td><?php echo $filename; ?></td>
                    <td><?php echo $date; ?></td>
                    <td><?php echo $type; ?></td>
                    <td><?php echo $data; ?></td>
                    <td>
                        <a href="<?php echo $download; ?>">Download</a>
                        <a href="<?php echo $link3.'&name='.$filename; ?>">Deleted</a>
                    </td>
                </tr>

                <?php endforeach; ?>

            <?php else: ?>

                <tr>
                    <td colspan="5">No backup</td>
                </tr>

            <?php endif; ?>

            </tbody>
        </table>

    </div>
</div>


<!--
<div>

	<ul class="subsubsub">


		<li><a href="http://localhost/wp/wp-admin/tools.php?page=backupwordpress&amp;hmbkp_schedule_id=default-1 " class="current">File daily <span class="count">(3)</span></a></li>


		<li><a href="http://localhost/wp/wp-admin/tools.php?page=backupwordpress&amp;hmbkp_schedule_id=default-2 ">Complete weekly <span class="count">(1)</span></a></li>


		<li><a href="http://localhost/wp/wp-admin/admin-ajax.php?action=hmbkp_add_schedule_load" class="colorbox cboxElement"> + add schedule</a></li>

	</ul>


	<div class="hmbkp_schedule" data-hmbkp-schedule-id="default-1">


<div class="hmbkp-schedule-sentence">

	Backup my <code title="Backups will be compressed and should be smaller than this.">17 MB</code> <span>files</span> daily at <span title="The next backup will be on July 9, 2013 at 11:00 pm">11:00 pm</span>, store only the last 14 backups on <span title="C:/xampp/htdocs/wp/wp-content/backupwordpress-f9aa273377-backups">this server</span>.

	<span class="hmbkp-status">Starting Backup <a href="http://localhost/wp/wp-admin/tools.php?page=backupwordpress&amp;action=hmbkp_cancel&amp;hmbkp_schedule_id=default-1">cancel</a></span>

	<div class="hmbkp-schedule-actions row-actions">

		<a href="http://localhost/wp/wp-admin/admin-ajax.php?action=hmbkp_edit_schedule_load&amp;hmbkp_schedule_id=default-1" class="colorbox cboxElement">Settings</a> |

			<a href="http://localhost/wp/wp-admin/admin-ajax.php?action=hmbkp_edit_schedule_excludes_load&amp;hmbkp_schedule_id=default-1" class="colorbox cboxElement">Excludes</a>  |


		<a href="http://localhost/wp/wp-admin/admin-ajax.php?action=hmbkp_run_schedule&amp;hmbkp_schedule_id=default-1" class="hmbkp-run">Run now</a>  |

		<a href="http://localhost/wp/wp-admin/tools.php?page=backupwordpress&amp;action=hmbkp_delete_schedule&amp;hmbkp_schedule_id=default-1&amp;_wpnonce=7fabf8faf3" class="delete-action">Delete</a>

	</div>


</div>
		<table class="widefat">

		    <thead>

				<tr>

					<th scope="col">3 backups completed</th>
		    		<th scope="col">Size</th>
		    		<th scope="col">Type</th>
		    		<th scope="col">Actions</th>

				</tr>

		    </thead>

		    <tbody>


	<tr class="hmbkp_manage_backups_row">

		<th scope="row">
			July 9, 2013 - 1:36 am		</th>

		<td class="code">
			8 MB		</td>

		<td>Files</td>

		<td>

			<a href="http://localhost/wp/wp-admin/tools.php?page=backupwordpress&amp;hmbkp_download_backup=QzoveGFtcHAvaHRkb2NzL3dwL3dwLWNvbnRlbnQvYmFja3Vwd29yZHByZXNzLWY5YWEyNzMzNzctYmFja3Vwcy9sb2NhbGhvc3R3cC1kZWZhdWx0LTEtZmlsZS0yMDEzLTA3LTA5LTAxLTM2LTA0LnppcA%3D%3D&amp;hmbkp_schedule_id=default-1&amp;_wpnonce=2bd58f5867">Download</a> |
			<a class="delete-action" href="http://localhost/wp/wp-admin/tools.php?page=backupwordpress&amp;hmbkp_delete_backup=QzoveGFtcHAvaHRkb2NzL3dwL3dwLWNvbnRlbnQvYmFja3Vwd29yZHByZXNzLWY5YWEyNzMzNzctYmFja3Vwcy9sb2NhbGhvc3R3cC1kZWZhdWx0LTEtZmlsZS0yMDEzLTA3LTA5LTAxLTM2LTA0LnppcA%3D%3D&amp;hmbkp_schedule_id=default-1&amp;_wpnonce=3e8c176011">Delete</a>

		</td>

	</tr>


	<tr class="hmbkp_manage_backups_row">

		<th scope="row">
			July 8, 2013 - 1:51 am		</th>

		<td class="code">
			8 MB		</td>

		<td>Files</td>

		<td>

			<a href="http://localhost/wp/wp-admin/tools.php?page=backupwordpress&amp;hmbkp_download_backup=QzoveGFtcHAvaHRkb2NzL3dwL3dwLWNvbnRlbnQvYmFja3Vwd29yZHByZXNzLWY5YWEyNzMzNzctYmFja3Vwcy9sb2NhbGhvc3R3cC1kZWZhdWx0LTEtZmlsZS0yMDEzLTA3LTA4LTAxLTUxLTIxLnppcA%3D%3D&amp;hmbkp_schedule_id=default-1&amp;_wpnonce=2bd58f5867">Download</a> |
			<a class="delete-action" href="http://localhost/wp/wp-admin/tools.php?page=backupwordpress&amp;hmbkp_delete_backup=QzoveGFtcHAvaHRkb2NzL3dwL3dwLWNvbnRlbnQvYmFja3Vwd29yZHByZXNzLWY5YWEyNzMzNzctYmFja3Vwcy9sb2NhbGhvc3R3cC1kZWZhdWx0LTEtZmlsZS0yMDEzLTA3LTA4LTAxLTUxLTIxLnppcA%3D%3D&amp;hmbkp_schedule_id=default-1&amp;_wpnonce=3e8c176011">Delete</a>

		</td>

	</tr>


	<tr class="hmbkp_manage_backups_row">

		<th scope="row">
			July 8, 2013 - 1:48 am		</th>

		<td class="code">
			100 kB		</td>

		<td>Database</td>

		<td>

			<a href="http://localhost/wp/wp-admin/tools.php?page=backupwordpress&amp;hmbkp_download_backup=QzoveGFtcHAvaHRkb2NzL3dwL3dwLWNvbnRlbnQvYmFja3Vwd29yZHByZXNzLWY5YWEyNzMzNzctYmFja3Vwcy9sb2NhbGhvc3R3cC1kZWZhdWx0LTEtZGF0YWJhc2UtMjAxMy0wNy0wOC0wMS00OC00OC56aXA%3D&amp;hmbkp_schedule_id=default-1&amp;_wpnonce=2bd58f5867">Download</a> |
			<a class="delete-action" href="http://localhost/wp/wp-admin/tools.php?page=backupwordpress&amp;hmbkp_delete_backup=QzoveGFtcHAvaHRkb2NzL3dwL3dwLWNvbnRlbnQvYmFja3Vwd29yZHByZXNzLWY5YWEyNzMzNzctYmFja3Vwcy9sb2NhbGhvc3R3cC1kZWZhdWx0LTEtZGF0YWJhc2UtMjAxMy0wNy0wOC0wMS00OC00OC56aXA%3D&amp;hmbkp_schedule_id=default-1&amp;_wpnonce=3e8c176011">Delete</a>

		</td>

	</tr>


		    </tbody>

		</table>

	</div>

</div>





<div class="wrap">
<div class="icon32" id="icon-themes"><br></div><h2>Edit Themes</h2>

<div class="fileedit-sub">
    <div class="alignleft">
        <h3>Twenty Twelve: Stylesheet <span>(style.css)</span></h3>
    </div>
    <div class="alignright">
        <form method="post" action="theme-editor.php">
            <strong><label for="theme">Select theme to edit: </label></strong>
            <select id="theme" name="theme">

                <option value="twentyeleven">Twenty Eleven</option>
                <option selected="selected" value="twentytwelve">Twenty Twelve</option>		</select>
            <input type="submit" value="Select" class="button" id="Submit" name="Submit">	</form>
    </div>
    <br class="clear">
</div>
<div id="templateside">
    <h3>Templates</h3>
    <ul>
        <li><a href="theme-editor.php?file=404.php&amp;theme=twentytwelve">404 Template<br><span class="nonessential">(404.php)</span></a></li>
        <li><a href="theme-editor.php?file=archive.php&amp;theme=twentytwelve">Archives<br><span class="nonessential">(archive.php)</span></a></li>
        <li><a href="theme-editor.php?file=author.php&amp;theme=twentytwelve">Author Template<br><span class="nonessential">(author.php)</span></a></li>
        <li><a href="theme-editor.php?file=category.php&amp;theme=twentytwelve">Category Template<br><span class="nonessential">(category.php)</span></a></li>
        <li><a href="theme-editor.php?file=comments.php&amp;theme=twentytwelve">Comments<br><span class="nonessential">(comments.php)</span></a></li>
        <li><a href="theme-editor.php?file=content-aside.php&amp;theme=twentytwelve">content-aside.php</a></li>
        <li><a href="theme-editor.php?file=content-image.php&amp;theme=twentytwelve">content-image.php</a></li>
        <li><a href="theme-editor.php?file=content-link.php&amp;theme=twentytwelve">content-link.php</a></li>
        <li><a href="theme-editor.php?file=content-none.php&amp;theme=twentytwelve">content-none.php</a></li>
        <li><a href="theme-editor.php?file=content-page.php&amp;theme=twentytwelve">content-page.php</a></li>
        <li><a href="theme-editor.php?file=content-quote.php&amp;theme=twentytwelve">content-quote.php</a></li>
        <li><a href="theme-editor.php?file=content-status.php&amp;theme=twentytwelve">content-status.php</a></li>
        <li><a href="theme-editor.php?file=content.php&amp;theme=twentytwelve">content.php</a></li>
        <li><a href="theme-editor.php?file=footer.php&amp;theme=twentytwelve">Footer<br><span class="nonessential">(footer.php)</span></a></li>
        <li><a href="theme-editor.php?file=functions.php&amp;theme=twentytwelve">Theme Functions<br><span class="nonessential">(functions.php)</span></a></li>
        <li><a href="theme-editor.php?file=header.php&amp;theme=twentytwelve">Header<br><span class="nonessential">(header.php)</span></a></li>
        <li><a href="theme-editor.php?file=image.php&amp;theme=twentytwelve">Image Attachment Template<br><span class="nonessential">(image.php)</span></a></li>
        <li><a href="theme-editor.php?file=inc%2Fcustom-header.php&amp;theme=twentytwelve">custom-header.php</a></li>
        <li><a href="theme-editor.php?file=index.php&amp;theme=twentytwelve">Main Index Template<br><span class="nonessential">(index.php)</span></a></li>
        <li><a href="theme-editor.php?file=page-templates%2Ffront-page.php&amp;theme=twentytwelve">Front Page Template Page Template<br><span class="nonessential">(page-templates/front-page.php)</span></a></li>
        <li><a href="theme-editor.php?file=page-templates%2Ffull-width.php&amp;theme=twentytwelve">Full-width Page Template, No Sidebar Page Template<br><span class="nonessential">(page-templates/full-width.php)</span></a></li>
        <li><a href="theme-editor.php?file=page.php&amp;theme=twentytwelve">Page Template<br><span class="nonessential">(page.php)</span></a></li>
        <li><a href="theme-editor.php?file=search.php&amp;theme=twentytwelve">Search Results<br><span class="nonessential">(search.php)</span></a></li>
        <li><a href="theme-editor.php?file=sidebar-front.php&amp;theme=twentytwelve">sidebar-front.php</a></li>
        <li><a href="theme-editor.php?file=sidebar.php&amp;theme=twentytwelve">Sidebar<br><span class="nonessential">(sidebar.php)</span></a></li>
        <li><a href="theme-editor.php?file=single.php&amp;theme=twentytwelve">Single Post<br><span class="nonessential">(single.php)</span></a></li>
        <li><a href="theme-editor.php?file=tag.php&amp;theme=twentytwelve">Tag Template<br><span class="nonessential">(tag.php)</span></a></li>
    </ul>
    <h3>Styles</h3>
    <ul>
        <li><a href="theme-editor.php?file=style.css&amp;theme=twentytwelve"><span class="highlight">Stylesheet<br><span class="nonessential">(style.css)</span></span></a></li>
        <li><a href="theme-editor.php?file=editor-style-rtl.css&amp;theme=twentytwelve">Visual Editor RTL Stylesheet<br><span class="nonessential">(editor-style-rtl.css)</span></a></li>
        <li><a href="theme-editor.php?file=editor-style.css&amp;theme=twentytwelve">Visual Editor Stylesheet<br><span class="nonessential">(editor-style.css)</span></a></li>
        <li><a href="theme-editor.php?file=rtl.css&amp;theme=twentytwelve">RTL Stylesheet<br><span class="nonessential">(rtl.css)</span></a></li>
    </ul>
</div>
<form method="post" action="theme-editor.php" id="template" name="template">
<input type="hidden" value="316ca06796" name="_wpnonce" id="_wpnonce"><input type="hidden" value="/wp/wp-admin/theme-editor.php" name="_wp_http_referer">		<div><textarea aria-describedby="newcontent-description" id="newcontent" name="newcontent" rows="30" cols="70">/*1
Theme Name: Twenty Twelve
Theme URI: http://wordpress.org/extend/themes/twentytwelve
Author: the WordPress team
Author URI: http://wordpress.org/
Description: The 2012 theme for WordPress is a fully responsive theme that looks great on any device. Features include a front page template with its own widgets, an optional display font, styling for post formats on both index and single views, and an optional no-sidebar page template. Make it yours with a custom menu, header image, and background.
Version: 1.1
License: GNU General Public License v2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
Tags: light, gray, white, one-column, two-columns, right-sidebar, flexible-width, custom-background, custom-header, custom-menu, editor-style, featured-images, flexible-header, full-width-template, microformats, post-formats, rtl-language-support, sticky-post, theme-options, translation-ready
Text Domain: twentytwelve

This theme, like WordPress, is licensed under the GPL.
Use it to make something cool, have fun, and share what you've learned with others.
*/

/* =Notes
--------------------------------------------------------------
This stylesheet uses rem values with a pixel fallback. The rem
values (and line heights) are calculated using two variables:

$rembase:     14;
$line-height: 24;

---------- Examples

* Use a pixel value with a rem fallback for font-size, padding, margins, etc.
padding: 5px 0;
padding: 0.357142857rem 0; (5 / $rembase)

* Set a font-size and then set a line-height based on the font-size
font-size: 16px
font-size: 1.142857143rem; (16 / $rembase)
line-height: 1.5; ($line-height / 16)

---------- Vertical spacing

Vertical spacing between most elements should use 24px or 48px
to maintain vertical rhythm:

.my-new-div {
margin: 24px 0;
margin: 1.714285714rem 0; ( 24 / $rembase )
}

---------- Further reading

http://snook.ca/archives/html_and_css/font-size-with-rem
http://blog.typekit.com/2011/11/09/type-study-sizing-the-legible-letter/


/* =Reset
-------------------------------------------------------------- */

html, body, div, span, applet, object, iframe, h1, h2, h3, h4, h5, h6, p, blockquote, pre, a, abbr, acronym, address, big, cite, code, del, dfn, em, img, ins, kbd, q, s, samp, small, strike, strong, sub, sup, tt, var, b, u, i, center, dl, dt, dd, ol, ul, li, fieldset, form, label, legend, table, caption, tbody, tfoot, thead, tr, th, td, article, aside, canvas, details, embed, figure, figcaption, footer, header, hgroup, menu, nav, output, ruby, section, summary, time, mark, audio, video {
margin: 0;
padding: 0;
border: 0;
font-size: 100%;
vertical-align: baseline;
}
body {
line-height: 1;
}
ol,
ul {
list-style: none;
}
blockquote,
q {
quotes: none;
}
blockquote:before,
blockquote:after,
q:before,
q:after {
content: '';
content: none;
}
table {
border-collapse: collapse;
border-spacing: 0;
}
caption,
th,
td {
font-weight: normal;
text-align: left;
}
h1,
h2,
h3,
h4,
h5,
h6 {
clear: both;
}
html {
overflow-y: scroll;
font-size: 100%;
-webkit-text-size-adjust: 100%;
-ms-text-size-adjust: 100%;
}
a:focus {
outline: thin dotted;
}
article,
aside,
details,
figcaption,
figure,
footer,
header,
hgroup,
nav,
section {
display: block;
}
audio,
canvas,
video {
display: inline-block;
}
audio:not([controls]) {
display: none;
}
del {
color: #333;
}
ins {
background: #fff9c0;
text-decoration: none;
}
hr {
background-color: #ccc;
border: 0;
height: 1px;
margin: 24px;
margin-bottom: 1.714285714rem;
}
sub,
sup {
font-size: 75%;
line-height: 0;
position: relative;
vertical-align: baseline;
}
sup {
top: -0.5em;
}
sub {
bottom: -0.25em;
}
small {
font-size: smaller;
}
img {
border: 0;
-ms-interpolation-mode: bicubic;
}

/* Clearing floats */
.clear:after,
.wrapper:after,
.format-status .entry-header:after {
clear: both;
}
.clear:before,
.clear:after,
.wrapper:before,
.wrapper:after,
.format-status .entry-header:before,
.format-status .entry-header:after {
display: table;
content: "";
}


/* =Repeatable patterns
-------------------------------------------------------------- */

/* Small headers */
.archive-title,
.page-title,
.widget-title,
.entry-content th,
.comment-content th {
font-size: 11px;
font-size: 0.785714286rem;
line-height: 2.181818182;
font-weight: bold;
text-transform: uppercase;
color: #636363;
}

/* Shared Post Format styling */
article.format-quote footer.entry-meta,
article.format-link footer.entry-meta,
article.format-status footer.entry-meta {
font-size: 11px;
font-size: 0.785714286rem;
line-height: 2.181818182;
}

/* Form fields, general styles first */
button,
input,
textarea {
border: 1px solid #ccc;
border-radius: 3px;
font-family: inherit;
padding: 6px;
padding: 0.428571429rem;
}
button,
input {
line-height: normal;
}
textarea {
font-size: 100%;
overflow: auto;
vertical-align: top;
}

/* Reset non-text input types */
input[type="checkbox"],
input[type="radio"],
input[type="file"],
input[type="hidden"],
input[type="image"],
input[type="color"] {
border: 0;
border-radius: 0;
padding: 0;
}

/* Buttons */
.menu-toggle,
input[type="submit"],
input[type="button"],
input[type="reset"],
article.post-password-required input[type=submit],
li.bypostauthor cite span {
padding: 6px 10px;
padding: 0.428571429rem 0.714285714rem;
font-size: 11px;
font-size: 0.785714286rem;
line-height: 1.428571429;
font-weight: normal;
color: #7c7c7c;
background-color: #e6e6e6;
background-repeat: repeat-x;
background-image: -moz-linear-gradient(top, #f4f4f4, #e6e6e6);
background-image: -ms-linear-gradient(top, #f4f4f4, #e6e6e6);
background-image: -webkit-linear-gradient(top, #f4f4f4, #e6e6e6);
background-image: -o-linear-gradient(top, #f4f4f4, #e6e6e6);
background-image: linear-gradient(top, #f4f4f4, #e6e6e6);
border: 1px solid #d2d2d2;
border-radius: 3px;
box-shadow: 0 1px 2px rgba(64, 64, 64, 0.1);
}
.menu-toggle,
button,
input[type="submit"],
input[type="button"],
input[type="reset"] {
cursor: pointer;
}
button[disabled],
input[disabled] {
cursor: default;
}
.menu-toggle:hover,
button:hover,
input[type="submit"]:hover,
input[type="button"]:hover,
input[type="reset"]:hover,
article.post-password-required input[type=submit]:hover {
color: #5e5e5e;
background-color: #ebebeb;
background-repeat: repeat-x;
background-image: -moz-linear-gradient(top, #f9f9f9, #ebebeb);
background-image: -ms-linear-gradient(top, #f9f9f9, #ebebeb);
background-image: -webkit-linear-gradient(top, #f9f9f9, #ebebeb);
background-image: -o-linear-gradient(top, #f9f9f9, #ebebeb);
background-image: linear-gradient(top, #f9f9f9, #ebebeb);
}
.menu-toggle:active,
.menu-toggle.toggled-on,
button:active,
input[type="submit"]:active,
input[type="button"]:active,
input[type="reset"]:active {
color: #757575;
background-color: #e1e1e1;
background-repeat: repeat-x;
background-image: -moz-linear-gradient(top, #ebebeb, #e1e1e1);
background-image: -ms-linear-gradient(top, #ebebeb, #e1e1e1);
background-image: -webkit-linear-gradient(top, #ebebeb, #e1e1e1);
background-image: -o-linear-gradient(top, #ebebeb, #e1e1e1);
background-image: linear-gradient(top, #ebebeb, #e1e1e1);
box-shadow: inset 0 0 8px 2px #c6c6c6, 0 1px 0 0 #f4f4f4;
border: none;
}
li.bypostauthor cite span {
color: #fff;
background-color: #21759b;
background-image: none;
border: 1px solid #1f6f93;
border-radius: 2px;
box-shadow: none;
padding: 0;
}

/* Responsive images */
.entry-content img,
.comment-content img,
.widget img {
max-width: 100%; /* Fluid images for posts, comments, and widgets */
}
img[class*="align"],
img[class*="wp-image-"],
img[class*="attachment-"] {
height: auto; /* Make sure images with WordPress-added height and width attributes are scaled correctly */
}
img.size-full,
img.size-large,
img.header-image,
img.wp-post-image {
max-width: 100%;
height: auto; /* Make sure images with WordPress-added height and width attributes are scaled correctly */
}

/* Make sure videos and embeds fit their containers */
embed,
iframe,
object,
video {
max-width: 100%;
}
.entry-content .twitter-tweet-rendered {
max-width: 100% !important; /* Override the Twitter embed fixed width */
}

/* Images */
.alignleft {
float: left;
}
.alignright {
float: right;
}
.aligncenter {
display: block;
margin-left: auto;
margin-right: auto;
}
.entry-content img,
.comment-content img,
.widget img,
img.header-image,
.author-avatar img,
img.wp-post-image {
/* Add fancy borders to all WordPress-added images but not things like badges and icons and the like */
border-radius: 3px;
box-shadow: 0 1px 4px rgba(0, 0, 0, 0.2);
}
.wp-caption {
max-width: 100%; /* Keep wide captions from overflowing their container. */
padding: 4px;
}
.wp-caption .wp-caption-text,
.gallery-caption,
.entry-caption {
font-style: italic;
font-size: 12px;
font-size: 0.857142857rem;
line-height: 2;
color: #757575;
}
img.wp-smiley,
.rsswidget img {
border: 0;
border-radius: 0;
box-shadow: none;
margin-bottom: 0;
margin-top: 0;
padding: 0;
}
.entry-content dl.gallery-item {
margin: 0;
}
.gallery-item a,
.gallery-caption {
width: 90%;
}
.gallery-item a {
display: block;
}
.gallery-caption a {
display: inline;
}
.gallery-columns-1 .gallery-item a {
max-width: 100%;
width: auto;
}
.gallery .gallery-icon img {
height: auto;
max-width: 90%;
padding: 5%;
}
.gallery-columns-1 .gallery-icon img {
padding: 3%;
}

/* Navigation */
.site-content nav {
clear: both;
line-height: 2;
overflow: hidden;
}
#nav-above {
padding: 24px 0;
padding: 1.714285714rem 0;
}
#nav-above {
display: none;
}
.paged #nav-above {
display: block;
}
.nav-previous,
.previous-image {
float: left;
width: 50%;
}
.nav-next,
.next-image {
float: right;
text-align: right;
width: 50%;
}
.nav-single + .comments-area,
#comment-nav-above {
margin: 48px 0;
margin: 3.428571429rem 0;
}

/* Author profiles */
.author .archive-header {
margin-bottom: 24px;
margin-bottom: 1.714285714rem;
}
.author-info {
border-top: 1px solid #ededed;
margin: 24px 0;
margin: 1.714285714rem 0;
padding-top: 24px;
padding-top: 1.714285714rem;
overflow: hidden;
}
.author-description p {
color: #757575;
font-size: 13px;
font-size: 0.928571429rem;
line-height: 1.846153846;
}
.author.archive .author-info {
border-top: 0;
margin: 0 0 48px;
margin: 0 0 3.428571429rem;
}
.author.archive .author-avatar {
margin-top: 0;
}


/* =Basic structure
-------------------------------------------------------------- */

/* Body, links, basics */
html {
font-size: 87.5%;
}
body {
font-size: 14px;
font-size: 1rem;
font-family: Helvetica, Arial, sans-serif;
text-rendering: optimizeLegibility;
color: #444;
}
body.custom-font-enabled {
font-family: "Open Sans", Helvetica, Arial, sans-serif;
}
a {
outline: none;
color: #21759b;
}
a:hover {
color: #0f3647;
}

/* Assistive text */
.assistive-text,
.site .screen-reader-text {
position: absolute !important;
clip: rect(1px, 1px, 1px, 1px);
}
.main-navigation .assistive-text:hover,
.main-navigation .assistive-text:active,
.main-navigation .assistive-text:focus {
background: #fff;
border: 2px solid #333;
border-radius: 3px;
clip: auto !important;
color: #000;
display: block;
font-size: 12px;
padding: 12px;
position: absolute;
top: 5px;
left: 5px;
z-index: 100000; /* Above WP toolbar */
}

/* Page structure */
.site {
padding: 0 24px;
padding: 0 1.714285714rem;
background-color: #fff;
}
.site-content {
margin: 24px 0 0;
margin: 1.714285714rem 0 0;
}
.widget-area {
margin: 24px 0 0;
margin: 1.714285714rem 0 0;
}

/* Header */
.site-header {
padding: 24px 0;
padding: 1.714285714rem 0;
}
.site-header h1,
.site-header h2 {
text-align: center;
}
.site-header h1 a,
.site-header h2 a {
color: #515151;
display: inline-block;
text-decoration: none;
}
.site-header h1 a:hover,
.site-header h2 a:hover {
color: #21759b;
}
.site-header h1 {
font-size: 24px;
font-size: 1.714285714rem;
line-height: 1.285714286;
margin-bottom: 14px;
margin-bottom: 1rem;
}
.site-header h2 {
font-weight: normal;
font-size: 13px;
font-size: 0.928571429rem;
line-height: 1.846153846;
color: #757575;
}
.header-image {
margin-top: 24px;
margin-top: 1.714285714rem;
}

/* Navigation Menu */
.main-navigation {
margin-top: 24px;
margin-top: 1.714285714rem;
text-align: center;
}
.main-navigation li {
margin-top: 24px;
margin-top: 1.714285714rem;
font-size: 12px;
font-size: 0.857142857rem;
line-height: 1.42857143;
}
.main-navigation a {
color: #5e5e5e;
}
.main-navigation a:hover {
color: #21759b;
}
.main-navigation ul.nav-menu,
.main-navigation div.nav-menu &gt; ul {
display: none;
}
.main-navigation ul.nav-menu.toggled-on,
.menu-toggle {
display: inline-block;
}

/* Banner */
section[role="banner"] {
margin-bottom: 48px;
margin-bottom: 3.428571429rem;
}

/* Sidebar */
.widget-area .widget {
-webkit-hyphens: auto;
-moz-hyphens: auto;
hyphens: auto;
margin-bottom: 48px;
margin-bottom: 3.428571429rem;
word-wrap: break-word;
}
.widget-area .widget h3 {
margin-bottom: 24px;
margin-bottom: 1.714285714rem;
}
.widget-area .widget p,
.widget-area .widget li,
.widget-area .widget .textwidget {
font-size: 13px;
font-size: 0.928571429rem;
line-height: 1.846153846;
}
.widget-area .widget p {
margin-bottom: 24px;
margin-bottom: 1.714285714rem;
}
.widget-area .textwidget ul {
list-style: disc outside;
margin: 0 0 24px;
margin: 0 0 1.714285714rem;
}
.widget-area .textwidget li {
margin-left: 36px;
margin-left: 2.571428571rem;
}
.widget-area .widget a {
color: #757575;
}
.widget-area .widget a:hover {
color: #21759b;
}
.widget-area #s {
width: 53.66666666666%; /* define a width to avoid dropping a wider submit button */
}

/* Footer */
footer[role="contentinfo"] {
border-top: 1px solid #ededed;
clear: both;
font-size: 12px;
font-size: 0.857142857rem;
line-height: 2;
max-width: 960px;
max-width: 68.571428571rem;
margin-top: 24px;
margin-top: 1.714285714rem;
margin-left: auto;
margin-right: auto;
padding: 24px 0;
padding: 1.714285714rem 0;
}
footer[role="contentinfo"] a {
color: #686868;
}
footer[role="contentinfo"] a:hover {
color: #21759b;
}


/* =Main content and comment content
-------------------------------------------------------------- */

.entry-meta {
clear: both;
}
.entry-header {
margin-bottom: 24px;
margin-bottom: 1.714285714rem;
}
.entry-header img.wp-post-image {
margin-bottom: 24px;
margin-bottom: 1.714285714rem;
}
.entry-header .entry-title {
font-size: 20px;
font-size: 1.428571429rem;
line-height: 1.2;
font-weight: normal;
}
.entry-header .entry-title a {
text-decoration: none;
}
.entry-header .entry-format {
margin-top: 24px;
margin-top: 1.714285714rem;
font-weight: normal;
}
.entry-header .comments-link {
margin-top: 24px;
margin-top: 1.714285714rem;
font-size: 13px;
font-size: 0.928571429rem;
line-height: 1.846153846;
color: #757575;
}
.comments-link a,
.entry-meta a {
color: #757575;
}
.comments-link a:hover,
.entry-meta a:hover {
color: #21759b;
}
article.sticky .featured-post {
border-top: 4px double #ededed;
border-bottom: 4px double #ededed;
color: #757575;
font-size: 13px;
font-size: 0.928571429rem;
line-height: 3.692307692;
margin-bottom: 24px;
margin-bottom: 1.714285714rem;
text-align: center;
}
.entry-content,
.entry-summary,
.mu_register {
line-height: 1.714285714;
}
.entry-content h1,
.comment-content h1,
.entry-content h2,
.comment-content h2,
.entry-content h3,
.comment-content h3,
.entry-content h4,
.comment-content h4,
.entry-content h5,
.comment-content h5,
.entry-content h6,
.comment-content h6 {
margin: 24px 0;
margin: 1.714285714rem 0;
line-height: 1.714285714;
}
.entry-content h1,
.comment-content h1 {
font-size: 21px;
font-size: 1.5rem;
line-height: 1.5;
}
.entry-content h2,
.comment-content h2,
.mu_register h2 {
font-size: 18px;
font-size: 1.285714286rem;
line-height: 1.6;
}
.entry-content h3,
.comment-content h3 {
font-size: 16px;
font-size: 1.142857143rem;
line-height: 1.846153846;
}
.entry-content h4,
.comment-content h4 {
font-size: 14px;
font-size: 1rem;
line-height: 1.846153846;
}
.entry-content h5,
.comment-content h5 {
font-size: 13px;
font-size: 0.928571429rem;
line-height: 1.846153846;
}
.entry-content h6,
.comment-content h6 {
font-size: 12px;
font-size: 0.857142857rem;
line-height: 1.846153846;
}
.entry-content p,
.entry-summary p,
.comment-content p,
.mu_register p {
margin: 0 0 24px;
margin: 0 0 1.714285714rem;
line-height: 1.714285714;
}
.entry-content ol,
.comment-content ol,
.entry-content ul,
.comment-content ul,
.mu_register ul {
margin: 0 0 24px;
margin: 0 0 1.714285714rem;
line-height: 1.714285714;
}
.entry-content ul ul,
.comment-content ul ul,
.entry-content ol ol,
.comment-content ol ol,
.entry-content ul ol,
.comment-content ul ol,
.entry-content ol ul,
.comment-content ol ul {
margin-bottom: 0;
}
.entry-content ul,
.comment-content ul,
.mu_register ul {
list-style: disc outside;
}
.entry-content ol,
.comment-content ol {
list-style: decimal outside;
}
.entry-content li,
.comment-content li,
.mu_register li {
margin: 0 0 0 36px;
margin: 0 0 0 2.571428571rem;
}
.entry-content blockquote,
.comment-content blockquote {
margin-bottom: 24px;
margin-bottom: 1.714285714rem;
padding: 24px;
padding: 1.714285714rem;
font-style: italic;
}
.entry-content blockquote p:last-child,
.comment-content blockquote p:last-child {
margin-bottom: 0;
}
.entry-content code,
.comment-content code {
font-family: Consolas, Monaco, Lucida Console, monospace;
font-size: 12px;
font-size: 0.857142857rem;
line-height: 2;
}
.entry-content pre,
.comment-content pre {
border: 1px solid #ededed;
color: #666;
font-family: Consolas, Monaco, Lucida Console, monospace;
font-size: 12px;
font-size: 0.857142857rem;
line-height: 1.714285714;
margin: 24px 0;
margin: 1.714285714rem 0;
overflow: auto;
padding: 24px;
padding: 1.714285714rem;
}
.entry-content pre code,
.comment-content pre code {
display: block;
}
.entry-content abbr,
.comment-content abbr,
.entry-content dfn,
.comment-content dfn,
.entry-content acronym,
.comment-content acronym {
border-bottom: 1px dotted #666;
cursor: help;
}
.entry-content address,
.comment-content address {
display: block;
line-height: 1.714285714;
margin: 0 0 24px;
margin: 0 0 1.714285714rem;
}
img.alignleft {
margin: 12px 24px 12px 0;
margin: 0.857142857rem 1.714285714rem 0.857142857rem 0;
}
img.alignright {
margin: 12px 0 12px 24px;
margin: 0.857142857rem 0 0.857142857rem 1.714285714rem;
}
img.aligncenter {
margin-top: 12px;
margin-top: 0.857142857rem;
margin-bottom: 12px;
margin-bottom: 0.857142857rem;
}
.entry-content embed,
.entry-content iframe,
.entry-content object,
.entry-content video {
margin-bottom: 24px;
margin-bottom: 1.714285714rem;
}
.entry-content dl,
.comment-content dl {
margin: 0 24px;
margin: 0 1.714285714rem;
}
.entry-content dt,
.comment-content dt {
font-weight: bold;
line-height: 1.714285714;
}
.entry-content dd,
.comment-content dd {
line-height: 1.714285714;
margin-bottom: 24px;
margin-bottom: 1.714285714rem;
}
.entry-content table,
.comment-content table {
border-bottom: 1px solid #ededed;
color: #757575;
font-size: 12px;
font-size: 0.857142857rem;
line-height: 2;
margin: 0 0 24px;
margin: 0 0 1.714285714rem;
width: 100%;
}
.entry-content table caption,
.comment-content table caption {
font-size: 16px;
font-size: 1.142857143rem;
margin: 24px 0;
margin: 1.714285714rem 0;
}
.entry-content td,
.comment-content td {
border-top: 1px solid #ededed;
padding: 6px 10px 6px 0;
}
.site-content article {
border-bottom: 4px double #ededed;
margin-bottom: 72px;
margin-bottom: 5.142857143rem;
padding-bottom: 24px;
padding-bottom: 1.714285714rem;
word-wrap: break-word;
-webkit-hyphens: auto;
-moz-hyphens: auto;
hyphens: auto;
}
.page-links {
clear: both;
line-height: 1.714285714;
}
footer.entry-meta {
margin-top: 24px;
margin-top: 1.714285714rem;
font-size: 13px;
font-size: 0.928571429rem;
line-height: 1.846153846;
color: #757575;
}
.single-author .entry-meta .by-author {
display: none;
}
.mu_register h2 {
color: #757575;
font-weight: normal;
}


/* =Archives
-------------------------------------------------------------- */

.archive-header,
.page-header {
margin-bottom: 48px;
margin-bottom: 3.428571429rem;
padding-bottom: 22px;
padding-bottom: 1.571428571rem;
border-bottom: 1px solid #ededed;
}
.archive-meta {
color: #757575;
font-size: 12px;
font-size: 0.857142857rem;
line-height: 2;
margin-top: 22px;
margin-top: 1.571428571rem;
}


/* =Single image attachment view
-------------------------------------------------------------- */

.article.attachment {
overflow: hidden;
}
.image-attachment div.attachment {
text-align: center;
}
.image-attachment div.attachment p {
text-align: center;
}
.image-attachment div.attachment img {
display: block;
height: auto;
margin: 0 auto;
max-width: 100%;
}
.image-attachment .entry-caption {
margin-top: 8px;
margin-top: 0.571428571rem;
}


/* =Aside post format
-------------------------------------------------------------- */

article.format-aside h1 {
margin-bottom: 24px;
margin-bottom: 1.714285714rem;
}
article.format-aside h1 a {
text-decoration: none;
color: #4d525a;
}
article.format-aside h1 a:hover {
color: #2e3542;
}
article.format-aside .aside {
padding: 24px 24px 0;
padding: 1.714285714rem;
background: #d2e0f9;
border-left: 22px solid #a8bfe8;
}
article.format-aside p {
font-size: 13px;
font-size: 0.928571429rem;
line-height: 1.846153846;
color: #4a5466;
}
article.format-aside blockquote:last-child,
article.format-aside p:last-child {
margin-bottom: 0;
}


/* =Post formats
-------------------------------------------------------------- */

/* Image posts */
article.format-image footer h1 {
font-size: 13px;
font-size: 0.928571429rem;
line-height: 1.846153846;
font-weight: normal;
}
article.format-image footer h2 {
font-size: 11px;
font-size: 0.785714286rem;
line-height: 2.181818182;
}
article.format-image footer a h2 {
font-weight: normal;
}

/* Link posts */
article.format-link header {
padding: 0 10px;
padding: 0 0.714285714rem;
float: right;
font-size: 11px;
font-size: 0.785714286rem;
line-height: 2.181818182;
font-weight: bold;
font-style: italic;
text-transform: uppercase;
color: #848484;
background-color: #ebebeb;
border-radius: 3px;
}
article.format-link .entry-content {
max-width: 80%;
float: left;
}
article.format-link .entry-content a {
font-size: 22px;
font-size: 1.571428571rem;
line-height: 1.090909091;
text-decoration: none;
}

/* Quote posts */
article.format-quote .entry-content p {
margin: 0;
padding-bottom: 24px;
padding-bottom: 1.714285714rem;
}
article.format-quote .entry-content blockquote {
display: block;
padding: 24px 24px 0;
padding: 1.714285714rem 1.714285714rem 0;
font-size: 15px;
font-size: 1.071428571rem;
line-height: 1.6;
font-style: normal;
color: #6a6a6a;
background: #efefef;
}

/* Status posts */
.format-status .entry-header {
margin-bottom: 24px;
margin-bottom: 1.714285714rem;
}
.format-status .entry-header header {
display: inline-block;
}
.format-status .entry-header h1 {
font-size: 15px;
font-size: 1.071428571rem;
font-weight: normal;
line-height: 1.6;
margin: 0;
}
.format-status .entry-header h2 {
font-size: 12px;
font-size: 0.857142857rem;
font-weight: normal;
line-height: 2;
margin: 0;
}
.format-status .entry-header header a {
color: #757575;
}
.format-status .entry-header header a:hover {
color: #21759b;
}
.format-status .entry-header img {
float: left;
margin-right: 21px;
margin-right: 1.5rem;
}


/* =Comments
-------------------------------------------------------------- */

.comments-title {
margin-bottom: 48px;
margin-bottom: 3.428571429rem;
font-size: 16px;
font-size: 1.142857143rem;
line-height: 1.5;
font-weight: normal;
}
.comments-area article {
margin: 24px 0;
margin: 1.714285714rem 0;
}
.comments-area article header {
margin: 0 0 48px;
margin: 0 0 3.428571429rem;
overflow: hidden;
position: relative;
}
.comments-area article header img {
float: left;
padding: 0;
line-height: 0;
}
.comments-area article header cite,
.comments-area article header time {
display: block;
margin-left: 85px;
margin-left: 6.071428571rem;
}
.comments-area article header cite {
font-style: normal;
font-size: 15px;
font-size: 1.071428571rem;
line-height: 1.42857143;
}
.comments-area article header time {
line-height: 1.714285714;
text-decoration: none;
font-size: 12px;
font-size: 0.857142857rem;
color: #5e5e5e;
}
.comments-area article header a {
text-decoration: none;
color: #5e5e5e;
}
.comments-area article header a:hover {
color: #21759b;
}
.comments-area article header cite a {
color: #444;
}
.comments-area article header cite a:hover {
text-decoration: underline;
}
.comments-area article header h4 {
position: absolute;
top: 0;
right: 0;
padding: 6px 12px;
padding: 0.428571429rem 0.857142857rem;
font-size: 12px;
font-size: 0.857142857rem;
font-weight: normal;
color: #fff;
background-color: #0088d0;
background-repeat: repeat-x;
background-image: -moz-linear-gradient(top, #009cee, #0088d0);
background-image: -ms-linear-gradient(top, #009cee, #0088d0);
background-image: -webkit-linear-gradient(top, #009cee, #0088d0);
background-image: -o-linear-gradient(top, #009cee, #0088d0);
background-image: linear-gradient(top, #009cee, #0088d0);
border-radius: 3px;
border: 1px solid #007cbd;
}
.comments-area li.bypostauthor cite span {
position: absolute;
margin-left: 5px;
margin-left: 0.357142857rem;
padding: 2px 5px;
padding: 0.142857143rem 0.357142857rem;
font-size: 10px;
font-size: 0.714285714rem;
}
a.comment-reply-link,
a.comment-edit-link {
color: #686868;
font-size: 13px;
font-size: 0.928571429rem;
line-height: 1.846153846;
}
a.comment-reply-link:hover,
a.comment-edit-link:hover {
color: #21759b;
}
.commentlist .pingback {
line-height: 1.714285714;
margin-bottom: 24px;
margin-bottom: 1.714285714rem;
}

/* Comment form */
#respond {
margin-top: 48px;
margin-top: 3.428571429rem;
}
#respond h3#reply-title {
font-size: 16px;
font-size: 1.142857143rem;
line-height: 1.5;
}
#respond h3#reply-title #cancel-comment-reply-link {
margin-left: 10px;
margin-left: 0.714285714rem;
font-weight: normal;
font-size: 12px;
font-size: 0.857142857rem;
}
#respond form {
margin: 24px 0;
margin: 1.714285714rem 0;
}
#respond form p {
margin: 11px 0;
margin: 0.785714286rem 0;
}
#respond form p.logged-in-as {
margin-bottom: 24px;
margin-bottom: 1.714285714rem;
}
#respond form label {
display: block;
line-height: 1.714285714;
}
#respond form input[type="text"],
#respond form textarea {
-moz-box-sizing: border-box;
box-sizing: border-box;
font-size: 12px;
font-size: 0.857142857rem;
line-height: 1.714285714;
padding: 10px;
padding: 0.714285714rem;
width: 100%;
}
#respond form p.form-allowed-tags {
margin: 0;
font-size: 12px;
font-size: 0.857142857rem;
line-height: 2;
color: #5e5e5e;
}
.required {
color: red;
}


/* =Front page template
-------------------------------------------------------------- */

.entry-page-image {
margin-bottom: 14px;
margin-bottom: 1rem;
}
.template-front-page .site-content article {
border: 0;
margin-bottom: 0;
}
.template-front-page .widget-area {
clear: both;
float: none;
width: auto;
padding-top: 24px;
padding-top: 1.714285714rem;
border-top: 1px solid #ededed;
}
.template-front-page .widget-area .widget li {
margin: 8px 0 0;
margin: 0.571428571rem 0 0;
font-size: 13px;
font-size: 0.928571429rem;
line-height: 1.714285714;
list-style-type: square;
list-style-position: inside;
}
.template-front-page .widget-area .widget li a {
color: #757575;
}
.template-front-page .widget-area .widget li a:hover {
color: #21759b;
}
.template-front-page .widget-area .widget_text img {
float: left;
margin: 8px 24px 8px 0;
margin: 0.571428571rem 1.714285714rem 0.571428571rem 0;
}


/* =Widgets
-------------------------------------------------------------- */

.widget-area .widget ul ul {
margin-left: 12px;
margin-left: 0.857142857rem;
}
.widget_rss li {
margin: 12px 0;
margin: 0.857142857rem 0;
}
.widget_recent_entries .post-date,
.widget_rss .rss-date {
color: #aaa;
font-size: 11px;
font-size: 0.785714286rem;
margin-left: 12px;
margin-left: 0.857142857rem;
}
#wp-calendar {
margin: 0;
width: 100%;
font-size: 13px;
font-size: 0.928571429rem;
line-height: 1.846153846;
color: #686868;
}
#wp-calendar th,
#wp-calendar td,
#wp-calendar caption {
text-align: left;
}
#wp-calendar #next {
padding-right: 24px;
padding-right: 1.714285714rem;
text-align: right;
}
.widget_search label {
display: block;
font-size: 13px;
font-size: 0.928571429rem;
line-height: 1.846153846;
}
.widget_twitter li {
list-style-type: none;
}
.widget_twitter .timesince {
display: block;
text-align: right;
}


/* =Plugins
----------------------------------------------- */

img#wpstats {
display: block;
margin: 0 auto 24px;
margin: 0 auto 1.714285714rem;
}


/* =Media queries
-------------------------------------------------------------- */

/* Minimum width of 600 pixels. */
@media screen and (min-width: 600px) {
.author-avatar {
float: left;
margin-top: 8px;
margin-top: 0.571428571rem;
}
.author-description {
float: right;
width: 80%;
}
.site {
margin: 0 auto;
max-width: 960px;
max-width: 68.571428571rem;
overflow: hidden;
}
.site-content {
float: left;
width: 65.104166667%;
}
body.template-front-page .site-content,
body.single-attachment .site-content,
body.full-width .site-content {
width: 100%;
}
.widget-area {
float: right;
width: 26.041666667%;
}
.site-header h1,
.site-header h2 {
text-align: left;
}
.site-header h1 {
font-size: 26px;
font-size: 1.857142857rem;
line-height: 1.846153846;
margin-bottom: 0;
}
.main-navigation ul.nav-menu,
.main-navigation div.nav-menu &gt; ul {
border-bottom: 1px solid #ededed;
border-top: 1px solid #ededed;
display: inline-block !important;
text-align: left;
width: 100%;
}
.main-navigation ul {
margin: 0;
text-indent: 0;
}
.main-navigation li a,
.main-navigation li {
display: inline-block;
text-decoration: none;
}
.main-navigation li a {
border-bottom: 0;
color: #6a6a6a;
line-height: 3.692307692;
text-transform: uppercase;
white-space: nowrap;
}
.main-navigation li a:hover {
color: #000;
}
.main-navigation li {
margin: 0 40px 0 0;
margin: 0 2.857142857rem 0 0;
position: relative;
}
.main-navigation li ul {
display: none;
margin: 0;
padding: 0;
position: absolute;
top: 100%;
z-index: 1;
}
.main-navigation li ul ul {
top: 0;
left: 100%;
}
.main-navigation ul li:hover &gt; ul {
border-left: 0;
display: block;
}
.main-navigation li ul li a {
background: #efefef;
border-bottom: 1px solid #ededed;
display: block;
font-size: 11px;
font-size: 0.785714286rem;
line-height: 2.181818182;
padding: 8px 10px;
padding: 0.571428571rem 0.714285714rem;
width: 180px;
width: 12.85714286rem;
white-space: normal;
}
.main-navigation li ul li a:hover {
background: #e3e3e3;
color: #444;
}
.main-navigation .current-menu-item &gt; a,
.main-navigation .current-menu-ancestor &gt; a,
.main-navigation .current_page_item &gt; a,
.main-navigation .current_page_ancestor &gt; a {
color: #636363;
font-weight: bold;
}
.menu-toggle {
display: none;
}
.entry-header .entry-title {
font-size: 22px;
font-size: 1.571428571rem;
}
#respond form input[type="text"] {
width: 46.333333333%;
}
#respond form textarea.blog-textarea {
width: 79.666666667%;
}
.template-front-page .site-content,
.template-front-page article {
overflow: hidden;
}
.template-front-page.has-post-thumbnail article {
float: left;
width: 47.916666667%;
}
.entry-page-image {
float: right;
margin-bottom: 0;
width: 47.916666667%;
}
.template-front-page .widget-area .widget,
.template-front-page.two-sidebars .widget-area .front-widgets {
float: left;
width: 51.875%;
margin-bottom: 24px;
margin-bottom: 1.714285714rem;
}
.template-front-page .widget-area .widget:nth-child(odd) {
clear: right;
}
.template-front-page .widget-area .widget:nth-child(even),
.template-front-page.two-sidebars .widget-area .front-widgets + .front-widgets {
float: right;
width: 39.0625%;
margin: 0 0 24px;
margin: 0 0 1.714285714rem;
}
.template-front-page.two-sidebars .widget,
.template-front-page.two-sidebars .widget:nth-child(even) {
float: none;
width: auto;
}
.commentlist .children {
margin-left: 48px;
margin-left: 3.428571429rem;
}
}

/* Minimum width of 960 pixels. */
@media screen and (min-width: 960px) {
body {
background-color: #e6e6e6;
}
body .site {
padding: 0 40px;
padding: 0 2.857142857rem;
margin-top: 48px;
margin-top: 3.428571429rem;
margin-bottom: 48px;
margin-bottom: 3.428571429rem;
box-shadow: 0 2px 6px rgba(100, 100, 100, 0.3);
}
body.custom-background-empty {
background-color: #fff;
}
body.custom-background-empty .site,
body.custom-background-white .site {
padding: 0;
margin-top: 0;
margin-bottom: 0;
box-shadow: none;
}
}


/* =Print
----------------------------------------------- */

@media print {
body {
background: none !important;
color: #000;
font-size: 10pt;
}
footer a[rel=bookmark]:link:after,
footer a[rel=bookmark]:visited:after {
content: " [" attr(href) "] "; /* Show URLs */
}
a {
text-decoration: none;
}
.entry-content img,
.comment-content img,
.author-avatar img,
img.wp-post-image {
border-radius: 0;
box-shadow: none;
}
.site {
clear: both !important;
display: block !important;
float: none !important;
max-width: 100%;
position: relative !important;
}
.site-header {
margin-bottom: 72px;
margin-bottom: 5.142857143rem;
text-align: left;
}
.site-header h1 {
font-size: 21pt;
line-height: 1;
text-align: left;
}
.site-header h2 {
color: #000;
font-size: 10pt;
text-align: left;
}
.site-header h1 a,
.site-header h2 a {
color: #000;
}
.author-avatar,
#colophon,
#respond,
.commentlist .comment-edit-link,
.commentlist .reply,
.entry-header .comments-link,
.entry-meta .edit-link a,
.page-link,
.site-content nav,
.widget-area,
img.header-image,
.main-navigation {
display: none;
}
.wrapper {
border-top: none;
box-shadow: none;
}
.site-content {
margin: 0;
width: auto;
}
.singular .entry-header .entry-meta {
position: static;
}
.singular .site-content,
.singular .entry-header,
.singular .entry-content,
.singular footer.entry-meta,
.singular .comments-title {
margin: 0;
width: 100%;
}
.entry-header .entry-title,
.entry-title,
.singular .entry-title {
font-size: 21pt;
}
footer.entry-meta,
footer.entry-meta a {
color: #444;
font-size: 10pt;
}
.author-description {
float: none;
width: auto;
}

/* Comments */
.commentlist &gt; li.comment {
background: none;
position: relative;
width: auto;
}
.commentlist .avatar {
height: 39px;
left: 2.2em;
top: 2.2em;
width: 39px;
}
.comments-area article header cite,
.comments-area article header time {
margin-left: 50px;
margin-left: 3.57142857rem;
}
}</textarea>
<input type="hidden" value="update" name="action">
<input type="hidden" value="style.css" name="file">
<input type="hidden" value="twentytwelve" name="theme">
<input type="hidden" value="0" id="scrollto" name="scrollto">
</div>

<div>
    <p class="submit"><input type="submit" value="Update File" class="button button-primary" id="submit" name="submit"></p>		</div>
</form>
<br class="clear">
</div>

-->