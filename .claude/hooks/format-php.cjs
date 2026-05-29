const { execSync } = require('child_process');
const path = require('path');

let raw = '';
process.stdin.on('data', chunk => { raw += chunk; });
process.stdin.on('end', () => {
    try {
        const data = JSON.parse(raw.replace(/^﻿/, ''));
        const filePath = (data.tool_input || {}).file_path || '';
        if (!filePath.endsWith('.php')) return;
        const pint = path.join(__dirname, '..', '..', 'vendor', 'bin', 'pint');
        execSync(`"${pint}" "${filePath}"`, { stdio: 'inherit' });
    } catch (e) {
        // silent — never block Claude on formatter failure
    }
});
