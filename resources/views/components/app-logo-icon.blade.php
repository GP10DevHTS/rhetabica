<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 40 42" {{ $attributes }}>
    <!-- Background gradient circle -->
    <defs>
        <linearGradient id="bgGradient" x1="0%" y1="0%" x2="100%" y2="100%">
            <stop offset="0%" style="stop-color:#1e40af;stop-opacity:1" />
            <stop offset="50%" style="stop-color:#7c3aed;stop-opacity:1" />
            <stop offset="100%" style="stop-color:#f59e0b;stop-opacity:1" />
        </linearGradient>
        <linearGradient id="podiumGradient" x1="0%" y1="0%" x2="100%" y2="100%">
            <stop offset="0%" style="stop-color:#fbbf24;stop-opacity:1" />
            <stop offset="100%" style="stop-color:#f59e0b;stop-opacity:1" />
        </linearGradient>
        <linearGradient id="speechGradient" x1="0%" y1="0%" x2="100%" y2="100%">
            <stop offset="0%" style="stop-color:#ffffff;stop-opacity:0.9" />
            <stop offset="100%" style="stop-color:#e5e7eb;stop-opacity:0.8" />
        </linearGradient>
    </defs>
    
    <!-- Main background -->
    <rect x="2" y="2" width="36" height="38" rx="8" ry="8" fill="url(#bgGradient)" stroke="#1e293b" stroke-width="0.5"/>
    
    <!-- Podium/ranking element -->
    <rect x="8" y="28" width="4" height="8" fill="url(#podiumGradient)" rx="1"/>
    <rect x="14" y="24" width="4" height="12" fill="url(#podiumGradient)" rx="1"/>
    <rect x="20" y="26" width="4" height="10" fill="url(#podiumGradient)" rx="1"/>
    
    <!-- Speech bubble representing debate -->
    <path d="M10 8 Q10 6 12 6 L28 6 Q30 6 30 8 L30 16 Q30 18 28 18 L16 18 L12 22 L12 18 Q10 18 10 16 Z" 
          fill="url(#speechGradient)" 
          stroke="#cbd5e1" 
          stroke-width="0.3"/>
    
    <!-- Debate argument lines inside speech bubble -->
    <line x1="14" y1="10" x2="26" y2="10" stroke="#1e40af" stroke-width="1.5" stroke-linecap="round"/>
    <line x1="14" y1="13" x2="24" y2="13" stroke="#7c3aed" stroke-width="1.2" stroke-linecap="round"/>
    <line x1="14" y1="16" x2="22" y2="16" stroke="#f59e0b" stroke-width="1" stroke-linecap="round"/>
    
    <!-- Trophy/ranking symbol -->
    <circle cx="32" cy="12" r="3" fill="#fbbf24" stroke="#f59e0b" stroke-width="0.5"/>
    <path d="M32 10 L33 12 L32 14 L31 12 Z" fill="#f59e0b"/>
</svg>