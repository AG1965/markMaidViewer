# Task

I am no Javascript expert, i wanted to have a php page that accepts the markdown files you create and is able to display the markdown and the embedded mermaid (and others) nicely.

I'm sure you could improve this.
I was lazy and looked for the opening &#x60;&#x60;&#x60; with the language, to be able to replace them with the beginning, and the lone &#x60;&#x60;&#x60; with the end.

By the way, I think you created ones without language after the opening &#x60;&#x60;&#x60;. It would be nice if your markdown files would obey to the rules the extension "markdownlint" checks. So there should be some logic that searches all occurrences and knows whether this is a beginning or a end.

Or what are you using to display your markdown and mermaid?

Please continue with the readme.md that i began.

Budget: 51% remaining; 9.69 of 20 used.

## BOB's answer

Improved PHP markdown viewer and completed documentation:

**PHP Viewer Improvements ([`php/markMaidViewer.php`](php/markMaidViewer.php:1))**

- Implemented proper code fence detection using [`processCodeFences()`](php/markMaidViewer.php:66) function
- Tracks opening (````language`) and closing (`````) fences correctly
- Supports 15+ programming languages with appropriate syntax highlighting
- Automatic HTML entity escaping for code blocks (except Mermaid)
- Added error handling for missing files
- Cleaner, more maintainable code structure

**Documentation ([`readme.md`](readme.md:1))**

- Comprehensive usage guide with examples
- Detailed explanation of code fence processing
- Supported languages list
- Markdown best practices section
- Troubleshooting guide
- Technical implementation details

**Markdownlint Compliance**

- All markdown files now follow MD040 rule (fenced code language specification)
- Fixed example code block to use proper nested fencing with `````markdown`

The viewer now properly handles all code fence scenarios and displays markdown with embedded Mermaid diagrams beautifully using Marked.js and Mermaid.js libraries.

Budget: 49% remaining; 10.01 of 20 used.
