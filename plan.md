### **Development Plan: Divi Achievements Showcase Module**

This document outlines the development phases and tasks required to build the "Divi Achievements Showcase" plugin as specified in `PRD.md`.

---

### **Phase 1: Project Scaffolding & Setup**

*Goal: Create the basic plugin structure and files required for a Divi extension.*

1.  **Create Plugin Directory:**
    *   The main directory will be `photo-with-title-and-description-on-divi`.

2.  **Create Core Plugin File:**
    *   Create `photo-with-title-and-description-on-divi.php`.
    *   Add the WordPress plugin header comment, including Plugin Name, Author (`Gurkha Technology`), and other standard fields.
    *   This file will be the entry point and will initialize the Divi extension.

3.  **Establish Folder Structure:**
    *   `includes/`: For PHP classes and module definitions.
    *   `js/`: For JavaScript files.
    *   `css/`: For stylesheets.
    *   `lib/`: For third-party libraries like Swiper.js.

4.  **Set up Divi Extension Boilerplate:**
    *   In the main plugin file, create the main extension class that extends `ET_Builder_Extension`.
    *   Hook into Divi to register the custom module.

---

### **Phase 2: Backend - Divi Module Definition**

*Goal: Define the module's settings, fields, and content structure within the Divi Builder.*

1.  **Create Module Class:**
    *   Create `includes/AchievementsShowcaseModule.php`.
    *   The class `AchievementsShowcaseModule` will extend `ET_Builder_Module`.

2.  **Define Module Fields (`get_fields`):**
    *   Set `child_slug` and `child_item_text` to enable repeatable child items.
    *   Define the fields for the **parent module** (global settings).

3.  **Define Child Item Fields:**
    *   Create `includes/AchievementsShowcaseItem.php` for the child module class.
    *   This class will extend `ET_Builder_Module`.
    *   Define the fields for each achievement slide: `title`, `description`, `media_type` (Image, Video URL, Self-Hosted), `image`, `video_url`, `self_hosted_video`.

4.  **Implement Design Tab Settings:**
    *   **Parent Module:** Add controls for navigation (arrows/dots), animation (autoplay/speed), and global text/layout styles to the `get_advanced_fields` method.
    *   **Child Module:** Add a Design tab to the child items to allow for per-slide overrides (background, text color, etc.).

---

### **Phase 3: Frontend - Rendering & Interactivity**

*Goal: Render the module on the frontend with all specified features and interactivity.*

1.  **Implement the `render` Method:**
    *   In `AchievementsShowcaseModule.php`, develop the `render` function.
    *   This function will generate the HTML structure for the Swiper.js slider (`swiper-container`, `swiper-wrapper`, `swiper-slide`).
    *   It will loop through the child items (`$this->props['content']`) and render each one as a slide.
    *   Conditionally render the media: `<img>` for images, and an embed `<iframe>` or `<video>` tag for videos. Implement the video fallback logic.

2.  **Integrate Swiper.js:**
    *   Download Swiper.js and place it in the `lib/` directory.
    *   Enqueue `swiper-bundle.min.js` and `swiper-bundle.min.css`.
    *   Create `js/frontend.js` to initialize Swiper.

3.  **Bridge PHP and JavaScript:**
    *   In the `render` method, use `wp_localize_script` to pass all necessary settings (autoplay, speed, navigation options, etc.) from the module's props to `frontend.js`.
    *   The JavaScript will read these settings to initialize Swiper.js dynamically for each module instance on the page.

4.  **Implement Styling:**
    *   Create `css/style.css` and enqueue it.
    *   Add CSS for the slider layout, navigation elements, text, and other components.
    *   The `render` method will add dynamic CSS classes or inline styles to handle the user's design choices (e.g., custom colors, per-slide backgrounds).

---

### **Phase 4: Testing & Refinement**

*Goal: Ensure the module is robust, bug-free, and meets all requirements.*

1.  **Functionality Testing:**
    *   Test all module settings in the Divi Builder.
    *   Verify that images and all video types render correctly.
    *   Test video fallback behavior with invalid links.
    *   Confirm that per-slide styling overrides global styles.

2.  **Responsiveness Testing:**
    *   Test the module on desktop, tablet, and mobile screen sizes using browser developer tools.
    *   Ensure the layout reflows correctly and remains usable.

3.  **Accessibility Testing (WCAG 2.1 AA):**
    *   Verify full keyboard navigation (Tab, Arrow Keys).
    *   Check that all interactive elements have proper ARIA labels.
    *   Test swipe gestures on a touch device or simulator.

4.  **Cross-Browser Compatibility:**
    *   Test the module in the latest versions of Chrome, Firefox, and Safari.

---

### **Phase 5: Documentation & Packaging**

*Goal: Prepare the plugin for release.*

1.  **Code Documentation:**
    *   Ensure all PHP classes and functions have clear, PHPDoc-style comments.

2.  **User Documentation:**
    *   Update the `README.md` file with comprehensive instructions on how to install and use the module.

3.  **Final Packaging:**
    *   Remove any development files.
    *   Create a distributable `.zip` file of the plugin directory.