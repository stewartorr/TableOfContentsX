# TableOfContentsX v1.3.1

This extra takes your MODX content and generates an ordered list table of contents based on the `h1`, `h2`, `h3`, `h4`, `h5`, and `h6` tags. 

It helps break down long articles and pages, making it easier for users to navigate directly to the sections they want.

![TableOfContentsX](docs/tableofcontentsx.png)


## Installation

The recommended method for installation is to use the MODX package manager browser and search for `TableOfContentsX` and install it there. If, for some reason, you cannot use the package manager, you can download the latest package and upload to `/core/packages/` and then *Search locally for Packages* under the *Download Extras* button and install from there.

## Snippet `TableOfContentsX`

This is the only snippet included in the package. It accepts a template variable, chunk, or placeholder and generates a table of contents.

The snippet can be used either as a simple output filter or as a snippet call with additional customisation options.

### Simple: TableOfContentsX as an output filter

Example of using TableOfContentsX as an output filter on the `[[*content]]` resource field:

- ``[[*content:TableOfContentsX=`content`]]``  
  Generates anchor IDs within the heading tags.

- ``[[*content:TableOfContentsX=`toc`]]``  
  Outputs the table of contents based on the detected headings.
 
### Advanced: TableOfContentsX as a snippet call

    [[TableOfContentsX? &input=`[[*content]]` &output=`toc` ]]

#### Options

| Property     | Description |
|-------------|------------|
| `&output`   | Output type: `toc` or `content` (default: `toc`) |
| `&tpl_outer` | Custom outer template for the TOC |
| `&tpl_inner` | Custom template for each TOC item |
| `&minlevel` | Minimum heading level to include (default: 1 = `h1`) |
| `&maxlevel` | Maximum heading level to include (default: 4 = `h4`) |

#### Examples:

    [[TableOfContentsX? &input=`[[*content]]` &output=`content` ]]

    [[TableOfContentsX? &input=`[[*content]]` &output=`toc` ]]

    [[TableOfContentsX? &input=`[[*content]]`
        &output=`toc` 
        &tpl_outer=`@INLINE <h2>My table of Contents</h2>
            <ol class="toc level-[[+level]]">
            [[+toc]]
            </ol>` 
        &tpl_inner=`@INLINE <li class="my-toc">
                <a href="[[+anchor]]">[[+title]]</a>
            </li>` 
        &maxlevel=`3` 
        &minlevel=`1` 
    ]]

#### Templates

You can also customise the output generated for the table of contents:

* `&tpl_outer` - the outer template for the list
* `&tpl_inner` - the inner template for each item

You can either specify a chunk name or use `@INLINE` to define the template directly as a string. Placeholders work as normal.

#### &tpl_outer

`@INLINE <ol class="toc level-[[+level]]">[[+toc]]</ol>`

* `[[+level]]` The current nesting level of the table of contents (reserved for future updates).
* `[[+toc]]` The generated list of links (see below)

#### &tpl_inner

`@INLINE <li><a href="[[+anchor]]">[[+title]]</a></li>`

* `[[+anchor]]` The page anchor used in the `<a>` tag
* `[[+title]]` The heading link title

## Documentation & more info

For information and support, check out my blog:

https://www.stewartorr.co.uk/tableofcontentsx

Created by Stewart Orr (https://www.stewartorr.co.uk).