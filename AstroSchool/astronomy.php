<?php include 'header.php'; ?>

<main class="container mt-4">
    <h1 class="text-center mb-4">🔭 Astronomy Resources</h1>
    <p class="text-center mb-4">Explore beginner to advanced guides on stargazing, telescope setup, and cosmic wonders.</p>

    <!-- Theme Buttons -->
    <div class="d-flex justify-content-center flex-wrap gap-2 mb-4">
        <button class="btn btn-outline-info" onclick="showAstronomyTheme('basics')">Getting Started</button>
        <button class="btn btn-outline-info" onclick="showAstronomyTheme('equipment')">Telescope Setup</button>
        <button class="btn btn-outline-info" onclick="showAstronomyTheme('planets')">Planets & Moons</button>
        <button class="btn btn-outline-info" onclick="showAstronomyTheme('deep')">Deep Sky Objects</button>
        <button class="btn btn-outline-info" onclick="showAstronomyTheme('events')">Celestial Events</button>
    </div>

    <!-- Theme Content -->
    <div id="astronomyContent" class="bg-secondary text-light p-4 rounded">
        <p>Select a topic to learn more about astronomy.</p>
    </div>
</main>
<script>
function showAstronomyTheme(theme) {
    const content = {
        basics: `
            <h1 class="mb-4 text-center">🌌 Getting Started with Astronomy</h1>

            <p>Astronomy is the oldest of the natural sciences, and yet, it remains one of the most thrilling and accessible hobbies you can explore today...</p>

            <h3>What Is Amateur Astronomy?</h3>
            <p>At its core, amateur astronomy involves observing celestial objects like stars, planets, the Moon, and galaxies...</p>

            <h3>Why Start Stargazing?</h3>
            <ul>
              <li>Improves patience and observation skills.</li>
              <li>Fosters scientific curiosity and critical thinking.</li>
              <li>Encourages time spent outdoors.</li>
              <li>Connects you with a global community.</li>
            </ul>

            <p>The night sky offers a sense of perspective, beauty, and peace...</p>

            <h3>Step 1: Learn the Sky</h3>
            <ul>
              <li><strong>Use Star Maps:</strong> Apps like Stellarium, SkySafari, or Star Walk show real-time sky positions.</li>
              <li><strong>Start with Constellations:</strong> Learn Ursa Major, Orion, Cassiopeia, and Cygnus.</li>
              <li><strong>Note the Ecliptic:</strong> This path helps you locate planets.</li>
              <li><strong>Escape Light Pollution:</strong> Use <a href="https://www.lightpollutionmap.info" target="_blank">light pollution maps</a> to find dark skies.</li>
            </ul>

            <h3>Step 2: Essential Tools</h3>
            <ul>
              <li><strong>Your Eyes:</strong> Get used to patterns and night vision.</li>
              <li><strong>Binoculars:</strong> 10x50s are great for the Moon and star clusters.</li>
              <li><strong>Mobile Apps:</strong> Work like a planetarium in your pocket.</li>
              <li><strong>Notebook:</strong> Logging observations improves learning.</li>
            </ul>

            <h3>Step 3: Explore the Moon and Planets</h3>
            <ul>
              <li><strong>Jupiter:</strong> Its moons are visible with binoculars.</li>
              <li><strong>Saturn:</strong> A telescope reveals its iconic rings.</li>
              <li><strong>Mars and Venus:</strong> Track their phases and motion.</li>
            </ul>
            <img src="uploads/moon_example.jpg" alt="Moon through telescope" class="img-fluid my-3 rounded shadow" style="max-width: 600px; width: 100%; height: auto; display: block; margin: 0 auto;">

            <h3>Step 4: Track Satellites, Eclipses & Meteors</h3>
            <ul>
              <li><strong>Meteor Showers:</strong> Watch the Perseids or Geminids peak yearly.</li>
              <li><strong>ISS Passes:</strong> Track via apps like Heavens-Above.</li>
              <li><strong>Eclipses:</strong> Solar and lunar eclipses are memorable experiences — use proper filters!</li>
            </ul>

            <h3>Step 5: Consider a Telescope</h3>
            <ul>
              <li><strong>Refractor:</strong> Best for the Moon and planets.</li>
              <li><strong>Reflector:</strong> Great for deep sky views.</li>
              <li><strong>Dobsonian:</strong> Simple, powerful, and budget-friendly.</li>
            </ul>
            <img src="uploads/telescope_example.jpg" alt="Basic Dobsonian telescope" class="img-fluid my-3 rounded shadow" style="max-width: 600px; width: 100%; height: auto; display: block; margin: 0 auto;">

            <h3>Learn Continuously</h3>
            <ul>
              <li>“<strong>Turn Left at Orion</strong>” by Guy Consolmagno</li>
              <li>“<strong>NightWatch</strong>” by Terence Dickinson</li>
            </ul>
            <p>Online resources: <a href="https://skyandtelescope.org" target="_blank">Sky & Telescope</a>, <a href="https://www.cloudynights.com" target="_blank">Cloudy Nights</a>.</p>

            <h3>Share the Sky</h3>
            <p>Invite friends or family. Host astronomy nights. Join a local club or visit a nearby observatory...</p>

            <h3 class="mt-5">Final Thoughts</h3>
            <p>Astronomy is a deeply personal and awe-inspiring pursuit...</p>
            <blockquote class="blockquote text-center mt-4">
              <p>“The cosmos is within us. We are made of star stuff.” – Carl Sagan</p>
            </blockquote>
        `,
        equipment: `<h3>Telescope Setup</h3><p>From refractors to Dobsonians, explore how to choose, assemble, and align your telescope for optimal viewing.</p>`,
        planets: `<h3>Planets & Moons</h3><p>Track visible planets, spot Jupiter's moons, and observe Saturn’s rings with tips on timing, equipment, and magnification.</p>`,
        deep: `<h3>Deep Sky Objects</h3><p>Explore star clusters, galaxies, and nebulae using telescopes and binoculars. Learn techniques to spot faint objects in dark skies.</p>`,
        events: `<h3>Celestial Events</h3><p>Never miss a meteor shower, lunar eclipse, or solar conjunction. Learn how to predict and safely observe these amazing sky shows.</p>`
    };

    document.getElementById("astronomyContent").innerHTML = content[theme];
}
</script>