<!DOCTYPE html>
<html lang="de">
<head>
  <meta charset="utf-8">
  <title>Markdown & Mermaid Viewer</title>

  <style>
    :root {
      color-scheme: light dark;
    }
    body {
      font-family: Calibri, system-ui, sans-serif;
      margin: 0;
      padding: 0;
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
      background-color: light-dark(white, #202020);
      min-width: 800px;
      width: 50%;
    }
    .code {
      padding: 1rem;
      border: 1px solid light-dark( #c0c0c0, #404040);
      border-radius: 0.5em;
      background-color: light-dark( #f0f0f0, #303030);
      overflow-x: auto;
    }
    div#content table {
      width: 100%;
      border-collapse: collapse;
    }
    div#content th {
      border-bottom: 1px solid #404040;
      padding: 0.5rem;
      text-align: left;
    }
    div#content td {
      border-bottom: 1px solid #c0c0c0;
      padding: 0.5rem;
    }
    pre {
      margin: 0;
      white-space: pre-wrap;
      word-wrap: break-word;
    }
  </style>

</head>

<body>
<?php
// -------------------------------------------------------------------------------------------------
/**
 * Process code fences in markdown content
 * Properly handles opening (```language) and closing (```) fences
 * 
 * @param string $mdfilecontents The markdown content to process
 * @return string The processed content with HTML tags
 */
function processCodeFences($mdfilecontents) {
    $lines = explode("\n", $mdfilecontents);
    $result = [];
    $inCodeBlock = false;
    $currentLanguage = '';
    
    foreach ($lines as $line) {
        // Check for code fence markers
        if (preg_match('/^```(\w*)/', $line, $matches)) {
            if (!$inCodeBlock) {
                // Opening fence
                $currentLanguage = $matches[1] ?? '';
                $inCodeBlock = true;
                
                // Determine the appropriate class/tag
                if ($currentLanguage === 'mermaid') {
                    $result[] = '<pre class="mermaid">';
                } elseif (in_array($currentLanguage, ['html', 'javascript', 'css', 'php', 'sql', 'rpgle', 'json', 'xml', 'bash', 'shell', 'python', 'java', 'c', 'cpp', 'csharp', 'typescript'])) {
                    $result[] = '<pre class="code">';
                } else {
                    // Generic code block (including empty language)
                    $result[] = '<pre class="code">';
                }
            } else {
                // Closing fence
                $result[] = '</pre>';
                $inCodeBlock = false;
                $currentLanguage = '';
            }
        } else {
            // Regular line - escape HTML entities if inside code block (except mermaid)
            if ($inCodeBlock && $currentLanguage !== 'mermaid') {
                $result[] = htmlspecialchars($line, ENT_QUOTES | ENT_HTML5, 'UTF-8');
            } else {
                $result[] = $line;
            }
        }
    }
    
    return implode("\n", $result);
}

// -------------------------------------------------------------------------------------------------
/**
 * Process custom link replacements for project-specific URLs
 * 
 * @param string $content The content to process
 * @param string $baseUrl The base URL for replacements
 * @return string The processed content
 */
function processCustomLinks($content, $baseUrl) {
    if (empty($baseUrl)) {
        return $content;
    }
    
    $url = '(https://' . $baseUrl . '/Sourcen/?sel_REPO=myRepository&sel_DIR=';
    $content = str_replace('(QDBFSRC/', $url . 'QDBFSRC&sel_MBR=', $content);
    $content = str_replace('(QRPGSRC/', $url . 'QRPGSRC&sel_MBR=', $content);
    $content = str_replace('.PF:', '.PF#L', $content);
    $content = str_replace('.LF:', '.LF#L', $content);
    $content = str_replace('.RPGLE:', '.RPGLE#L', $content);
    $content = str_replace('.SQLRPGLE:', '.SQLRPGLE#L', $content);
    
    return $content;
}

// -------------------------------------------------------------------------------------------------
// Main execution
// -------------------------------------------------------------------------------------------------

// Get markdown filename from request
$mdfilename = $_REQUEST['md'] ?? '';
if (empty($mdfilename)) {
    die('Error: Parameter "md" must specify the markdown file name');
}

// Check if file exists
if (!file_exists($mdfilename)) {
    die('Error: File "' . htmlspecialchars($mdfilename) . '" not found');
}

// Get base URL from environment
$BASEURL = getenv('BASEURL') ?? '';

// Read and process markdown file
$mdfilecontents = file_get_contents($mdfilename);

// Process code fences (handles opening/closing properly)
$mdfilecontents = processCodeFences($mdfilecontents);

// Process custom links if base URL is set
$mdfilecontents = processCustomLinks($mdfilecontents, $BASEURL);

// Output the content
echo '<div id="content">' . $mdfilecontents . '</div>';
?>

  <!-- Markdown parser -->
  <script src="https://cdn.jsdelivr.net/npm/marked/lib/marked.umd.js"></script>
  
  <!-- Mermaid diagram renderer -->
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
    
    // Parse markdown content
    let md = document.getElementById('content');
    md.innerHTML = marked.parse(md.innerHTML);

    // Render mermaid diagrams
    render();

  </script>
</body>
</html>
