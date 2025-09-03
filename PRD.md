### **Product Requirements Document: Divi Achievements Showcase Module (v1.1)**

**1. Overview**

This document outlines the requirements for a new premium WordPress plugin that provides a custom Divi module named "Achievements Showcase." This module will allow Divi users to display a collection of achievements, portfolio pieces, or testimonials in a visually appealing and navigable slider format. Each achievement will consist of a media item (image or video) accompanied by a title and a description.

**2. Target Audience**

*   **Primary Users:** WordPress administrators and website developers who use the Divi theme to build websites for themselves or their clients.
*   **End-Users:** Website visitors who will view and interact with the achievements showcase on the front end.

**3. User Stories**

*   **As a website administrator, I want to:**
    *   Easily add, edit, reorder, and delete individual achievement items directly within the Divi module.
    *   Choose between uploading a static image, embedding a video from a URL, or using a self-hosted WordPress video.
    *   Add a clear title and a detailed description for every achievement.
    *   Customize the global appearance of the module, and also override styles like background or text color for each achievement individually.
    *   Enable autoplay and control its speed.
    *   Be confident that the module is fully accessible and looks great on all devices.

*   **As a website visitor, I want to:**
    *   View the achievements in a clean, easy-to-understand format.
    *   Navigate through achievements smoothly using arrows, dots, keyboard commands, or swipe gestures on my touch device.
    *   Have a seamless viewing experience whether I'm on my phone or a large monitor.

**4. Functional Requirements**

**4.1. Editor Experience & Data Management**

*   **Data Model:** For version 1.0, achievements will be managed as repeatable fields **within the Divi module instance**. This provides a simple, self-contained user experience ideal for most use cases. Management via a dedicated Custom Post Type (CPT) is noted as a future enhancement for users needing to manage a large number of achievements or reuse them across multiple pages.
*   **Structure:** The module will be a "parent" that holds repeatable "child" items. Each child item represents one achievement and will contain its own Content, Design, and Advanced tabs.

**4.2. Content Settings (Per-Achievement)**

*   **Title:** Standard text input.
*   **Description:** Textarea or Rich Text Editor.
*   **Media Type:** Select/radio button:
    *   **Image:** Standard Divi image upload field.
    *   **Video (URL):** Text input for YouTube/Vimeo URLs.
    *   **Video (Self-Hosted):** WordPress Media Library video selection.
*   **Video Fallback:** If a video URL is invalid, private, or broken, the module must not crash. It should gracefully hide the video area or display a user-friendly placeholder.

**4.3. Design & Styling**

*   **Global Styling (Main Design Tab):**
    *   **Layout:** Options to show/hide navigation arrows and pagination dots.
    *   **Navigation:** Full styling for arrows (icon, color, size, background) and dots (color, active state).
    *   **Animation:**
        *   Toggle for **Autoplay**.
        *   Number input for **Autoplay Speed** (in milliseconds).
    *   **Text:** Global styling for Title and Description text.
    *   **Sizing, Spacing, Borders, Box Shadow:** Standard Divi controls for the module as a whole.
*   **Per-Slide Styling (Child Item Design Tab):**
    *   Users can override global styles for individual achievements.
    *   **Customization:** Options to set a unique **background**, **text color**, border, etc., for a specific slide to make it stand out.

**4.4. Frontend Behavior & Navigation**

*   **Layout:** A horizontal slider powered by the **Swiper.js** library, chosen for its performance, rich features, and strong accessibility support.
*   **Navigation Methods:**
    *   **Arrows:** Clickable next/previous buttons.
    *   **Pagination:** Clickable dots.
    *   **Keyboard:** Fully navigable using Tab and Arrow keys.
    *   **Swipe:** Intuitive swipe gestures on touch-enabled devices.

**5. Non-Functional Requirements**

*   **Compatibility:** Must be compatible with the latest versions of WordPress and the Divi theme.
*   **Code Quality:** Adhere to WordPress and Divi API best practices. All inputs sanitized, all outputs escaped.
*   **Performance:**
    *   Lazy loading for all images.
    *   Efficiently embed videos to minimize performance impact.
    *   The Swiper.js library will be loaded conditionally and optimized.
*   **Accessibility:**
    *   Targeting **WCAG 2.1 AA** compliance.
    *   Slider controls will have appropriate ARIA labels.
    *   Content changes will be announced to screen readers.
    *   Full keyboard control (Tab to focus the slider, Left/Right arrows to navigate slides).

**6. Future Enhancements (Out of Scope for V1)**

*   **Dedicated CPT:** A settings option to switch to a Custom Post Type for managing achievements globally.
*   **Grid Layout:** An alternative display option to show achievements in a filterable grid.
*   **Lightbox Integration:** Clicking an item opens it in a full-screen lightbox.
*   **Advanced Animations:** More sophisticated transition effects between slides.
*   **Categorization:** Ability to add categories/tags to achievements for filtering.
