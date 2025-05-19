import './bootstrap';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();

// Leaderboard real-time updates
document.addEventListener('DOMContentLoaded', function() {
    const leaderboardElement = document.getElementById('leaderboard');
    
    if (leaderboardElement) {
        window.Echo.channel('leaderboard')
            .listen('SubmissionUpdated', (e) => {
                if (e.event === 'leaderboard.updated') {
                    // Reload the leaderboard data
                    fetch('/leaderboard', {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    })
                    .then(response => response.text())
                    .then(html => {
                        const parser = new DOMParser();
                        const doc = parser.parseFromString(html, 'text/html');
                        const newLeaderboard = doc.getElementById('leaderboard-body');
                        
                        if (newLeaderboard) {
                            const currentLeaderboard = document.getElementById('leaderboard-body');
                            currentLeaderboard.innerHTML = newLeaderboard.innerHTML;
                        }
                    })
                    .catch(error => {
                        console.error('Error fetching updated leaderboard:', error);
                    });
                }
            });
    }
});
