<!DOCTYPE html>
<html lang="de">
<head>
  <meta charset="utf-8">
  <title>Mermaid Viewer</title>

  <style>
    :root {
      color-scheme: light dark;
    }
    body {
      font-family: Calibri, system-ui, sans-serif;
    }
    h1 {
      border-bottom: 1px solid #c0c0c0;
      padding-bottom: 6px;
    }
    h2 {
      border-bottom: 1px solid #c0c0c0;
      padding-bottom: 6px;
    }
    div#content {
      margin: 0 1rem 1rem 1rem;
      padding: 1rem;
      border: 2px solid #808080;
      border-radius: 1em;
      background-color: light-dark( white, #202020);
      min-width:800px;
      width:50%;
    }
    .code {
      padding: 1rem;
      border: 1px solid light-dark( #c0c0c0, #404040);
      border-radius: 0.5em;
      background-color: light-dark( #f0f0f0, #303030);
    }
    div#content table {
      width:100%;
      border-collapse: collapse;
    }
    div#content th {
      border-bottom: 1px solid #404040;
    }
    div#content td {
      border-bottom: 1px solid #c0c0c0;
    }
  </style>

  <!-- <script type="module">
    import mermaid from 'https://cdn.jsdelivr.net/npm/mermaid@11/dist/mermaid.esm.min.mjs';

    mermaid.initialize({
      startOnLoad: false,
      theme: "default"
    });
  </script> -->

</head>

<body>
<?php
// -------------------------------------------------------------------------------------------------
function escapeCodeblocks($search, $mdfilecontents) {
    $pos2 = 0;    
    while (($pos1 = strpos($mdfilecontents, '```' . $search, $pos2)) !== false) {
        $pos2 = strpos($mdfilecontents, '```', $pos1 + 3);
        if ($pos2 === false) break;
        
        $mdfilecontents = substr($mdfilecontents, 0, $pos1 - 1) .
        htmlentities(substr($mdfilecontents, $pos1, $pos2 - $pos1)) .
        substr($mdfilecontents, $pos2);
    }
    return $mdfilecontents;
}
// -------------------------------------------------------------------------------------------------
$mdfilename = $_REQUEST['md'] ?? '';
if ('' == $mdfilename) {
  die('Parameter "md" mit dem Namen des md-files angeben');
}
$BASEURL = getenv('BASEURL') ?? '';

$mdfilecontents = file_get_contents($mdfilename);
$mdfilecontents = str_replace('```mermaid', '<pre class="mermaid">', $mdfilecontents);
$mdfilecontents = str_replace('```rpgle', '<pre class="code">', $mdfilecontents);
$mdfilecontents = str_replace('```sql', '<pre class="code">', $mdfilecontents);
$mdfilecontents = str_replace('```pre', '<pre>', $mdfilecontents);
$mdfilecontents = escapeCodeblocks('html', $mdfilecontents);
$mdfilecontents = str_replace('```html', '<pre class="code">', $mdfilecontents);
$mdfilecontents = escapeCodeblocks('javascript', $mdfilecontents);
$mdfilecontents = str_replace('```javascript', '<pre class="code">', $mdfilecontents);
$mdfilecontents = str_replace('```css', '<pre class="code">', $mdfilecontents);
$mdfilecontents = str_replace('```', '</pre>', $mdfilecontents);
$url = '(https://' . $BASEURL . '/Sourcen/?sel_REPO=myRepository&sel_DIR=';
$mdfilecontents = str_replace('(QDBFSRC/', $url . 'QDBFSRC&sel_MBR=', $mdfilecontents);
$mdfilecontents = str_replace('(QRPGSRC/', $url . 'QRPGSRC&sel_MBR=', $mdfilecontents);
$mdfilecontents = str_replace('.PF:', '.PF#L', $mdfilecontents);
$mdfilecontents = str_replace('.LF:', '.LF#L', $mdfilecontents);
$mdfilecontents = str_replace('.RPGLE:', '.RPGLE#L', $mdfilecontents);
$mdfilecontents = str_replace('.SQLRPGLE:', '.SQLRPGLE#L', $mdfilecontents);
echo'<div id="content">' . $mdfilecontents . '</div>';
?>
  <script src="https://cdn.jsdelivr.net/npm/marked/lib/marked.umd.js"></script>
  <script type="module">
    import mermaid from 'https://cdn.jsdelivr.net/npm/mermaid@11/dist/mermaid.esm.min.mjs';

    mermaid.initialize({
      startOnLoad: false,
      theme: "default"
    });

    async function render() {
      const mermaid_nodes = document.getElementsByClassName("mermaid");
      await mermaid.run({ nodes: mermaid_nodes });
    }
    
    let md = document.getElementById('content');
    let content = md.innerHTML;
    md.innerHTML = marked.parse(md.innerHTML);

    render();

  </script>
</body>
</html>
