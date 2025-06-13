<?php include 'header.php'; ?>

<main class="container mt-4">
    <h1 class="text-center mb-4">📸 Astrophotography Tutorials</h1>
    <p class="text-center mb-4">Step-by-step guides on capturing deep-sky objects, stacking, and post-processing.</p>

    <!-- Theme Buttons -->
    <div class="d-flex justify-content-center flex-wrap gap-2 mb-4">
        <button class="btn btn-outline-info" onclick="showAstroPhotoTheme('gear')">Gear & Setup</button>
        <button class="btn btn-outline-info" onclick="showAstroPhotoTheme('capture')">Capturing</button>
        <button class="btn btn-outline-info" onclick="showAstroPhotoTheme('stack')">Stacking Images</button>
        <button class="btn btn-outline-info" onclick="showAstroPhotoTheme('edit')">Post-Processing</button>
        <button class="btn btn-outline-info" onclick="showAstroPhotoTheme('tips')">Tips & Tricks</button>
    </div>

    <!-- Content Display -->
    <div id="photoContent" class="bg-secondary text-light p-4 rounded">
        <p>Select a topic to learn how to photograph the night sky like a pro.</p>
    </div>
</main>

<script>
function showAstroPhotoTheme(theme) {
    const content = {
        gear: `
            <h3>Gear & Setup</h3>
            <img src="uploads/setup.jpg" alt="Astrophotography Gear" class="img-fluid rounded mb-3" />
            <p>Getting started in astrophotography begins with understanding your equipment:</p>
            <ul>
                <li><strong>Camera:</strong> DSLR or mirrorless. Consider ZWO ASI for advanced users.</li>
                <li><strong>Telescope:</strong> Refractor or Newtonian for different needs.</li>
                <li><strong>Mount:</strong> An equatorial mount (like EQ6-R) is essential.</li>
                <li><strong>Accessories:</strong> Tripod, dew heater, guiding scope, Bahtinov mask.</li>
            </ul>
            <p>Assemble and balance indoors first. Get familiar before field use.</p>
        `,

        capture: `
            <h3>Capturing the Sky</h3>
            <img src="uploads/capture.jpg" alt="Capturing Stars" class="img-fluid rounded mb-3" />
            <p>Here's how to get clear and accurate night sky images:</p>
            <ul>
                <li><strong>Location:</strong> Dark skies, minimal light pollution.</li>
                <li><strong>Settings:</strong> ISO 1600–3200, f/2.8–f/4, 15–30s exposure.</li>
                <li><strong>Focus:</strong> Live view + zoom on a bright star or Bahtinov mask.</li>
                <li><strong>Tracking:</strong> Needed for >30s exposures — use guide cameras/software.</li>
            </ul>
            <p>Capture 10–100+ frames for best results in stacking.</p>
        `,

        stack: `
            <h3>Stacking Images</h3>
            <img src="uploads/stacking.jpg" alt="Image Stacking Example" class="img-fluid rounded mb-3" />
            <p>Combine exposures to reduce noise and enhance detail:</p>
            <ol>
                <li>Capture light, dark, flat, and bias frames.</li>
                <li>Use software like <strong>DeepSkyStacker</strong>, <strong>Siril</strong>, or <strong>AstroPixelProcessor</strong>.</li>
                <li>Align and stack to create a master file (.TIF).</li>
            </ol>
            <p>More frames = smoother and more detailed images.</p>
        `,

        edit: `
            <h3>Post-Processing</h3>
            <img src="uploads/postprocessing.jpg" alt="Post-Processing in Photoshop" class="img-fluid rounded mb-3" />
            <p>Reveal hidden details and colors from your stack:</p>
            <ul>
                <li><strong>Histogram Stretching:</strong> Brighten faint areas carefully.</li>
                <li><strong>Color Calibration:</strong> Adjust for realistic and pleasing hues.</li>
                <li><strong>Noise Reduction:</strong> Apply selectively to the background.</li>
                <li><strong>Star Reduction:</strong> Use masks or StarNet++ plugin.</li>
                <li><strong>Curves/Levels:</strong> Fine-tune shadows, highlights, and balance.</li>
            </ul>
            <p>Processing is creative and technical — don’t rush it!</p>
        `,

        tips: `
            <h3>Tips & Tricks</h3>
            <img src="uploads/tips.jpg" alt="Astrophotography Field Tips" class="img-fluid rounded mb-3" />
            <p>Boost results and comfort with these tips:</p>
            <ul>
                <li>Use <strong>Stellarium</strong> or <strong>SkySafari</strong> to plan your session.</li>
                <li>Wear warm clothes and bring a checklist.</li>
                <li>Use red light to preserve night vision.</li>
                <li>Take notes on gear/settings — helps next time.</li>
                <li>Join local astronomy or astrophoto groups!</li>
            </ul>
        `
    };
    document.getElementById("photoContent").innerHTML = content[theme];
}
</script>

<?php include 'footer.php'; ?>
