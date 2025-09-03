# Technical Documentation: Divi Achievements Showcase

This document provides a technical overview of the "Achievements Showcase" Divi module plugin. It is intended for developers who are looking to understand the codebase, maintain it, or contribute to it.

## 1. Project Philosophy & Architecture

The plugin is designed with three core principles in mind:

1.  **Ease of Use:** The primary data model uses Divi's native parent/child module structure. Achievements are stored directly within the module's data on the page where it is placed. This avoids the need for a separate Custom Post Type (CPT), making it simple for users to manage one-off showcases without cluttering their WordPress admin panel.

2.  **Leverage Divi's Framework:** The plugin relies heavily on the Divi Builder's API for creating settings, handling data, and applying styles. This ensures maximum compatibility and a familiar experience for Divi users.

3.  **Performance First:** Assets (CSS/JS) are loaded conditionally only on pages where the module is actively used. The popular and lightweight Swiper.js library was chosen for the slider functionality due to its performance and rich feature set.

## 2. File & Directory Structure

The project is organized into a standard WordPress plugin structure:

-   `/photo-with-title-and-description-on-divi.php`
    -   **Purpose:** The main plugin entry point. It handles plugin initialization, registers the Divi extension, and contains the primary asset-loading logic.

-   `/includes/`
    -   **Purpose:** Contains all PHP class definitions for the Divi modules.
    -   `AchievementsShowcaseModule.php`: The Parent Module class. This class defines the slider container and its global settings.
    -   `AchievementsShowcaseItem.php`: The Child Module class. This defines the structure and settings for a single slide.

-   `/js/`
    -   `frontend.js`: Contains the JavaScript responsible for initializing the Swiper.js slider on the frontend.

-   `/css/`
    -   `style.css`: Contains all CSS for the module, including frontend layout/styling and specific styles for the Visual Builder backend.

-   `/lib/`
    -   **Purpose:** Holds third-party libraries. Currently contains the `swiper-bundle.min.js` and `swiper-bundle.min.css` files.

-   `/readme.txt`
    -   **Purpose:** The official readme file for the WordPress.org plugin repository. It contains metadata, descriptions, and changelogs.

## 3. Core Classes & Logic

### `PTD_Extension` (Main Plugin Class)

-   **Location:** `photo-with-title-and-description-on-divi.php`
-   **Purpose:** This class extends Divi's `ET_Builder_Extension` to register the custom modules with the Divi Builder.
-   **Key Method (`enqueue_assets`):** This method is hooked into `wp_enqueue_scripts`. It first registers all plugin assets (`wp_register_script`/`wp_register_style`). It then checks if the current page is a singular post/page and if it contains the module's shortcode (`ptd_achievements_showcase`). Only if both conditions are true does it enqueue the assets for loading. This is the core of the plugin's performance strategy.

### `AchievementsShowcaseModule` (Parent Module)

-   **Location:** `includes/AchievementsShowcaseModule.php`
-   **Purpose:** Defines the main slider container that holds the individual achievement slides.
-   **Key Logic:** Its `render()` method is responsible for generating the HTML wrapper for the slider, including the Swiper.js container and navigation elements. It passes all slider settings (like autoplay speed) to the frontend via a `data-slider-settings` HTML attribute.

### `AchievementsShowcaseItem` (Child Module)

-   **Location:** `includes/AchievementsShowcaseItem.php`
-   **Purpose:** Defines a single slide. Its `get_fields()` method declares all the content options (title, description, media type, etc.).
-   **Key Logic:** The `render()` method generates the HTML for one `swiper-slide`. It contains the conditional logic to display an image, an oEmbed video (YouTube/Vimeo), or a self-hosted video based on the user's selection.

## 4. Frontend Interaction (PHP to JavaScript)

The bridge between the Divi module settings (configured in PHP) and the frontend slider (initialized in JavaScript) is a `data-` attribute.

1.  **PHP Side:** In `AchievementsShowcaseModule::render()`, an array of slider settings is created from `$this->props`. This array is JSON-encoded and embedded into a `data-slider-settings` attribute on the main wrapper `<div>`.

2.  **JS Side:** In `frontend.js`, the script finds each instance of the slider module. It reads the content of the `data-slider-settings` attribute, parses the JSON string back into a JavaScript object, and uses this object to provide the options for the `new Swiper()` initialization.

This approach is self-contained and works reliably with Divi's rendering process and our conditional asset loading.

## 5. Styling Approach

-   **Global Styles:** All default styles for the slider, text, and navigation are in `css/style.css`.
-   **Divi Theming:** The styles respect Divi's built-in "Text Color" setting by using the `.et_pb_bg_layout_light` (dark text) and `.et_pb_bg_layout_dark` (light text) classes that Divi adds to the module.
-   **Visual Builder UI:** To improve the authoring experience, `style.css` contains specific rules prefixed with `.et-fb-root`. These styles, like the placeholder for empty items, only apply within the Divi Visual Builder and do not affect the frontend.
