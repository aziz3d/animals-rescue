{{-- Accessibility Testing Component --}}
{{-- This component can be included during development to test accessibility features --}}

@if(app()->environment('local'))
<div class="fixed bottom-4 right-4 z-50 no-print" id="accessibility-tester">
    <button 
        class="bg-blue-600 text-white p-3 rounded-full shadow-lg hover:bg-blue-700 focus-visible"
        onclick="toggleAccessibilityPanel()"
        aria-label="Toggle accessibility testing panel">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
    </button>
    
    <div id="accessibility-panel" class="hidden absolute bottom-16 right-0 bg-white border border-gray-300 rounded-lg shadow-xl p-4 w-80 max-h-96 overflow-y-auto">
        <h3 class="font-bold text-lg mb-4">Accessibility Testing</h3>
        
        <div class="space-y-3">
            <button onclick="testKeyboardNavigation()" class="w-full text-left p-2 bg-gray-100 rounded hover:bg-gray-200 focus-visible">
                Test Keyboard Navigation
            </button>
            
            <button onclick="testScreenReaderContent()" class="w-full text-left p-2 bg-gray-100 rounded hover:bg-gray-200 focus-visible">
                Check Screen Reader Content
            </button>
            
            <button onclick="testColorContrast()" class="w-full text-left p-2 bg-gray-100 rounded hover:bg-gray-200 focus-visible">
                Test Color Contrast
            </button>
            
            <button onclick="testFocusIndicators()" class="w-full text-left p-2 bg-gray-100 rounded hover:bg-gray-200 focus-visible">
                Test Focus Indicators
            </button>
            
            <button onclick="testFormLabels()" class="w-full text-left p-2 bg-gray-100 rounded hover:bg-gray-200 focus-visible">
                Check Form Labels
            </button>
            
            <button onclick="testImageAltText()" class="w-full text-left p-2 bg-gray-100 rounded hover:bg-gray-200 focus-visible">
                Check Image Alt Text
            </button>
            
            <button onclick="testHeadingStructure()" class="w-full text-left p-2 bg-gray-100 rounded hover:bg-gray-200 focus-visible">
                Test Heading Structure
            </button>
            
            <button onclick="simulateScreenReader()" class="w-full text-left p-2 bg-gray-100 rounded hover:bg-gray-200 focus-visible">
                Simulate Screen Reader
            </button>
        </div>
        
        <div id="test-results" class="mt-4 p-3 bg-gray-50 rounded text-sm max-h-32 overflow-y-auto hidden">
            <h4 class="font-semibold mb-2">Test Results:</h4>
            <div id="results-content"></div>
        </div>
    </div>
</div>

<script>
function toggleAccessibilityPanel() {
    const panel = document.getElementById('accessibility-panel');
    panel.classList.toggle('hidden');
}

function showResults(content) {
    const results = document.getElementById('test-results');
    const resultsContent = document.getElementById('results-content');
    resultsContent.innerHTML = content;
    results.classList.remove('hidden');
}

function testKeyboardNavigation() {
    const focusableElements = document.querySelectorAll(
        'a, button, input, textarea, select, [tabindex]:not([tabindex="-1"])'
    );
    
    let issues = [];
    focusableElements.forEach((el, index) => {
        if (!el.offsetParent && el.offsetWidth === 0 && el.offsetHeight === 0) return;
        
        if (getComputedStyle(el).outline === 'none' && !el.classList.contains('focus-visible')) {
            issues.push(`Element ${index + 1}: Missing focus indicator`);
        }
    });
    
    const result = issues.length === 0 
        ? `✅ All ${focusableElements.length} focusable elements have proper focus indicators`
        : `❌ Found ${issues.length} issues:<br>${issues.join('<br>')}`;
    
    showResults(result);
}

function testScreenReaderContent() {
    const issues = [];
    
    // Check for images without alt text
    const images = document.querySelectorAll('img');
    images.forEach((img, index) => {
        if (!img.alt && !img.getAttribute('aria-label') && !img.getAttribute('aria-labelledby')) {
            issues.push(`Image ${index + 1}: Missing alt text`);
        }
    });
    
    // Check for buttons without accessible names
    const buttons = document.querySelectorAll('button');
    buttons.forEach((btn, index) => {
        if (!btn.textContent.trim() && !btn.getAttribute('aria-label') && !btn.getAttribute('aria-labelledby')) {
            issues.push(`Button ${index + 1}: Missing accessible name`);
        }
    });
    
    const result = issues.length === 0 
        ? '✅ All elements have proper screen reader content'
        : `❌ Found ${issues.length} issues:<br>${issues.join('<br>')}`;
    
    showResults(result);
}

function testColorContrast() {
    // This is a simplified test - in production, you'd use a proper contrast checking library
    const textElements = document.querySelectorAll('p, span, div, a, button, label, h1, h2, h3, h4, h5, h6');
    let lowContrastCount = 0;
    
    textElements.forEach(el => {
        const styles = getComputedStyle(el);
        const color = styles.color;
        const backgroundColor = styles.backgroundColor;
        
        // Simple heuristic - this would need proper contrast calculation in production
        if (color === 'rgb(128, 128, 128)' || color.includes('gray')) {
            lowContrastCount++;
        }
    });
    
    const result = lowContrastCount === 0 
        ? '✅ No obvious low contrast issues found'
        : `⚠️ Found ${lowContrastCount} potentially low contrast elements`;
    
    showResults(result);
}

function testFocusIndicators() {
    const focusableElements = document.querySelectorAll('a, button, input, textarea, select');
    let missingFocus = 0;
    
    focusableElements.forEach(el => {
        const styles = getComputedStyle(el);
        if (styles.outline === 'none' && !el.classList.contains('focus-visible')) {
            missingFocus++;
        }
    });
    
    const result = missingFocus === 0 
        ? '✅ All focusable elements have focus indicators'
        : `❌ ${missingFocus} elements missing focus indicators`;
    
    showResults(result);
}

function testFormLabels() {
    const inputs = document.querySelectorAll('input, textarea, select');
    const issues = [];
    
    inputs.forEach((input, index) => {
        const id = input.id;
        const label = id ? document.querySelector(`label[for="${id}"]`) : null;
        const ariaLabel = input.getAttribute('aria-label');
        const ariaLabelledby = input.getAttribute('aria-labelledby');
        
        if (!label && !ariaLabel && !ariaLabelledby) {
            issues.push(`Input ${index + 1}: Missing label`);
        }
    });
    
    const result = issues.length === 0 
        ? `✅ All ${inputs.length} form inputs have proper labels`
        : `❌ Found ${issues.length} issues:<br>${issues.join('<br>')}`;
    
    showResults(result);
}

function testImageAltText() {
    const images = document.querySelectorAll('img');
    const issues = [];
    
    images.forEach((img, index) => {
        if (!img.alt) {
            issues.push(`Image ${index + 1}: Missing alt attribute`);
        } else if (img.alt.toLowerCase().includes('image') || img.alt.toLowerCase().includes('picture')) {
            issues.push(`Image ${index + 1}: Alt text contains redundant words`);
        }
    });
    
    const result = issues.length === 0 
        ? `✅ All ${images.length} images have proper alt text`
        : `❌ Found ${issues.length} issues:<br>${issues.join('<br>')}`;
    
    showResults(result);
}

function testHeadingStructure() {
    const headings = document.querySelectorAll('h1, h2, h3, h4, h5, h6');
    const issues = [];
    let previousLevel = 0;
    
    headings.forEach((heading, index) => {
        const level = parseInt(heading.tagName.charAt(1));
        
        if (index === 0 && level !== 1) {
            issues.push('First heading should be h1');
        }
        
        if (level > previousLevel + 1) {
            issues.push(`Heading ${index + 1}: Skipped heading level (${previousLevel} to ${level})`);
        }
        
        previousLevel = level;
    });
    
    const result = issues.length === 0 
        ? `✅ Heading structure is correct (${headings.length} headings)`
        : `❌ Found ${issues.length} issues:<br>${issues.join('<br>')}`;
    
    showResults(result);
}

function simulateScreenReader() {
    const content = [];
    
    // Get page title
    content.push(`Page title: ${document.title}`);
    
    // Get main landmarks
    const main = document.querySelector('main');
    if (main) content.push('Main content region found');
    
    const nav = document.querySelector('nav');
    if (nav) content.push('Navigation region found');
    
    // Get headings
    const headings = document.querySelectorAll('h1, h2, h3, h4, h5, h6');
    content.push(`Found ${headings.length} headings`);
    
    // Get links
    const links = document.querySelectorAll('a');
    content.push(`Found ${links.length} links`);
    
    // Get form elements
    const forms = document.querySelectorAll('form');
    content.push(`Found ${forms.length} forms`);
    
    const result = content.join('<br>');
    showResults(result);
}

// Close panel when clicking outside
document.addEventListener('click', function(e) {
    const panel = document.getElementById('accessibility-panel');
    const tester = document.getElementById('accessibility-tester');
    
    if (!tester.contains(e.target)) {
        panel.classList.add('hidden');
    }
});
</script>
@endif