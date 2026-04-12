
// Global variable to store all destinations
let allDestinations = [];
let currentCategory = 'all';

// Fetch top destinations cards
fetch('../../../src/api/routes/api.php?uri=/api/top-destinations')
  .then(async res => {
    if (!res.ok) {
      throw new Error(`HTTP ${res.status}: ${res.statusText}`);
    }
    const text = await res.text();
    const cleanText = text.replace(/^\uFEFF/, '').trim();
    return JSON.parse(cleanText);
  })
  .then(cards => {
    allDestinations = cards || [];
    renderDestinations(currentCategory);
    setupCategoryButtons();
  })
  .catch(err => {
    console.error('Error loading top destinations:', err);
    const container1 = document.getElementById('top-destinations-cards');
    container1.innerHTML = '<p style="text-align: center; padding: 40px; color: #666;">Unable to load destinations.</p>';
  });

function renderDestinations(category) {
  const container1 = document.getElementById('top-destinations-cards');
  
  if (!allDestinations || allDestinations.length === 0) {
    container1.innerHTML = '<p style="text-align: center; padding: 40px; color: #666;">No top destinations available yet.</p>';
    return;
  }

  // Filter based on category
  let filteredDestinations;
  if (category === 'all') {
    // Show both categories, limit to 6 each
    const cities = allDestinations.filter(card => card.category === 'city').slice(0, 6);
    const municipalities = allDestinations.filter(card => card.category === 'municipality').slice(0, 6);
    filteredDestinations = [...cities, ...municipalities];
  } else if (category === 'cities') {
    filteredDestinations = allDestinations.filter(card => card.category === 'city').slice(0, 6);
  } else if (category === 'municipalities') {
    filteredDestinations = allDestinations.filter(card => card.category === 'municipality').slice(0, 6);
  }

  if (filteredDestinations.length === 0) {
    container1.innerHTML = `<p style="text-align: center; padding: 40px; color: #666;">No ${category} destinations available yet.</p>`;
    return;
  }

  // Generate HTML
  let html = '';
  
  if (category === 'all') {
    // Show both categories separately
    const cities = allDestinations.filter(card => card.category === 'city').slice(0, 6);
    const municipalities = allDestinations.filter(card => card.category === 'municipality').slice(0, 6);
    
    if (cities.length > 0) {
      // Split cities into rows of 3 cards each
      for (let i = 0; i < cities.length; i += 3) {
        const rowCards = cities.slice(i, i + 3);
        html += `
          <div style="margin-bottom: 40px;">
            <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px;">
              ${rowCards.map(card => `
                <div class="six-cards-border">
                  <img src="../${card.image_url || 'assets/img/placeholder.svg'}" alt="${card.name}" onerror="this.src='../assets/img/placeholder.svg'">
                  <div class="top-destinations-text-overlay">
                    <h3>${card.location}: ${card.name}</h3>
                  </div>
                </div>
              `).join('')}
            </div>
          </div>
        `;
      }
    }
    
    if (municipalities.length > 0) {
      // Split municipalities into rows of 3 cards each
      for (let i = 0; i < municipalities.length; i += 3) {
        const rowCards = municipalities.slice(i, i + 3);
        html += `
          <div>
            <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px;">
              ${rowCards.map(card => `
                <div class="six-cards-border">
                  <img src="../${card.image_url || 'assets/img/placeholder.svg'}" alt="${card.name}" onerror="this.src='../assets/img/placeholder.svg'">
                  <div class="top-destinations-text-overlay">
                    <h3>${card.location}: ${card.name}</h3>
                  </div>
                </div>
              `).join('')}
            </div>
          </div>
        `;
      }
    }
  } else {
    // Show single category without header - split into rows of 3 cards each
    for (let i = 0; i < filteredDestinations.length; i += 3) {
      const rowCards = filteredDestinations.slice(i, i + 3);
      html += `
        <div style="margin-bottom: 40px;">
          <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px;">
            ${rowCards.map(card => `
              <div class="six-cards-border">
                <img src="../${card.image_url || 'assets/img/placeholder.svg'}" alt="${card.name}" onerror="this.src='../assets/img/placeholder.svg'">
                <div class="top-destinations-text-overlay">
                  <h3>${card.location}: ${card.name}</h3>
                </div>
              </div>
            `).join('')}
          </div>
        </div>
      `;
    }
  }

  // Fade transition
  container1.classList.add('fade-out');
  
  setTimeout(() => {
    container1.innerHTML = html;
    container1.classList.remove('fade-out');
  }, 300);
}

function setupCategoryButtons() {
  const buttons = document.querySelectorAll('.category-btn');
  
  buttons.forEach(button => {
    button.addEventListener('click', function(e) {
      e.preventDefault();
      
      // Remove active class from all buttons
      buttons.forEach(btn => btn.classList.remove('active'));
      
      // Add active class to clicked button
      this.classList.add('active');
      
      // Update current category and render
      currentCategory = this.dataset.category;
      renderDestinations(currentCategory);
    });
  });
}

// Fetch three cards
fetch('../../../src/api/routes/api.php?uri=/api/three-cards')
  .then(async res => {
    if (!res.ok) {
      throw new Error(`HTTP ${res.status}: ${res.statusText}`);
    }
    const text = await res.text();
    const cleanText = text.replace(/^\uFEFF/, '').trim();
    return JSON.parse(cleanText);
  })
  .then(cards => {
    const container2 = document.getElementById('three-cards');
    if (cards && cards.length > 0) {
      container2.innerHTML = cards.map(card => `
        <div class="three-cards-border">
          <img src="../${card.image_url || 'assets/img/placeholder.svg'}" alt="${card.title}" onerror="this.src='../assets/img/placeholder.svg'">
        </div>
      `).join('');
    } else {
      container2.innerHTML = '<p style="text-align: center; padding: 40px; color: #666;">No cards available yet.</p>';
    }
  })
  .catch(err => {
    console.error('Error loading three cards:', err);
    const container2 = document.getElementById('three-cards');
    container2.innerHTML = '<p style="text-align: center; padding: 40px; color: #666;">Unable to load cards.</p>';
  });

//all cards
fetch('../api/cards')
  .then(res => res.json())
  .then(cards => {
    const container3 = document.getElementById('cards');
    if (container3) {
      container3.innerHTML = cards.map(card => `
        <div class="cards">
          <div>
            <img src="../${card.image_url}" alt="${card.name}">
          </div>

          ${card.recommended ? '<h2>Recommended</h2>' : ''}

          <span class="material-symbols-outlined">bookmark</span>

          <div class="card-info">
            <h1>${card.location}:</h1>
            <h1>${card.name}</h1>
          </div>
        </div>
      `).join('');
    }
  })
  .catch(err => console.error('Error loading all cards:', err));
