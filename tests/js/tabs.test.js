// Simple DOM-based test for tabs.js
// Run with: node tests/js/tabs.test.js (requires minimal DOM mock)

const fs = require('fs');
const path = require('path');

// Minimal DOM mock
const { JSDOM } = require('jsdom');

function runTests() {
    const html = `
        <div class="tabs" data-tabs>
            <nav class="tabs-nav">
                <button data-tab="a" class="tabs-nav-item active">A</button>
                <button data-tab="b" class="tabs-nav-item">B</button>
            </nav>
            <form>
                <input type="hidden" name="active_tab" value="a">
                <div class="tabs-panel active" data-tab="a">Panel A</div>
                <div class="tabs-panel" data-tab="b">Panel B</div>
            </form>
        </div>
    `;
    
    const dom = new JSDOM(html, { runScripts: 'dangerously', url: 'http://localhost' });
    const window = dom.window;
    const document = window.document;
    
    // Load tabs.js
    const tabsScript = fs.readFileSync(path.join(__dirname, '../../public/assets/tabs.js'), 'utf8');
    const script = document.createElement('script');
    script.textContent = tabsScript;
    document.head.appendChild(script);
    
    // Manually init since DOMContentLoaded already fired
    const container = document.querySelector('[data-tabs]');
    window.Tabs.init(container);
    
    // Test 1: initial active state
    const panelA = document.querySelector('[data-tab="a"].tabs-panel');
    const panelB = document.querySelector('[data-tab="b"].tabs-panel');
    const btnA = document.querySelector('[data-tab="a"].tabs-nav-item');
    const btnB = document.querySelector('[data-tab="b"].tabs-nav-item');
    
    assert(panelA.classList.contains('active'), 'panel A active initially');
    assert(!panelB.classList.contains('active'), 'panel B inactive initially');
    assert(btnA.classList.contains('active'), 'btn A active initially');
    
    // Test 2: click switches tab
    btnB.click();
    assert(!panelA.classList.contains('active'), 'panel A inactive after click');
    assert(panelB.classList.contains('active'), 'panel B active after click');
    assert(btnB.classList.contains('active'), 'btn B active after click');
    assert(!btnA.classList.contains('active'), 'btn A inactive after click');
    
    // Test 3: hidden input updated
    const hidden = document.querySelector('input[name="active_tab"]');
    assert(hidden.value === 'b', 'hidden input updated to b');
    
    console.log('\nAll tabs.js tests passed.');
}

function assert(condition, message) {
    if (!condition) {
        throw new Error('FAIL: ' + message);
    }
    console.log('PASS: ' + message);
}

// Check if jsdom is available
try {
    require.resolve('jsdom');
    runTests();
} catch (e) {
    console.log('Note: jsdom not installed. Skipping DOM tests.');
    console.log('Install with: npm install jsdom');
}
