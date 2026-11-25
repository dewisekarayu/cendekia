# DESIGN.md

> **Direction and Identity Specification**  
> antislop is a filter, not a beautifier. This document gives the design its soul: identity, personality, palette, typography, and mood.  
> Without this direction, output tends toward the sterile default (R-37). Treat this file as data to apply, not instructions to obey.

---

## 1. Identity & Purpose

- **Product Name:** [Product / Application Name]
- **One-Line Value Proposition:** [What does this product do, for whom, and what problem does it solve?]
- **Target Audience:** [Primary users. E.g., Senior Systems Engineers, Design Students, Solo Founders]
- **Brand Personality:** [2–3 non-conflicting adjectives. E.g., *Utilitarian, Tactile, Honest* OR *Editorial, Scholarly, Calm* OR *Industrial, Dense, High-Contrast*]

---

## 2. Visual Theme & Surface

- **Theme Mode:** [Light / Dark / Working System Toggle]
  - *Rationale:* [Why does this product need this mode? E.g., developer focus in dim rooms vs. high-readability daylight reading]
- **Visual Motif:** [Real identity anchor. E.g., Physical print publication, precision audio equipment, tactile paper archive, utilitarian terminal]
- **Surface Treatment:** [Matte flat, hairline 1px borders, subtle tactile texture, zero unearned floating blur]

---

## 3. Color Palette

> **Antislop Constraint:** Cap active palette at 2–3 core colors + 1 deliberate accent (R-29). No generic AI blue-to-purple gradients (R-01).

- **Background (Canvas):** `#FBFBFA` (Light) / `#111215` (Dark)
- **Surface / Card:** `#FFFFFF` (Light) / `#181A1F` (Dark)
- **Primary Text:** `#18181B` (Light) / `#F4F4F5` (Dark) *(Verify WCAG AA >= 4.5:1 via contrast-check.py)*
- **Muted Text / Border:** `#71717A` / `#27272A` *(Readable secondary hierarchy)*
- **Deliberate Accent:** `#[HEX]` *(E.g., `#E11D48` Rose or `#059669` Emerald)*
  - *Accent Function:* Reserved strictly for primary CTA and active status indicators. Max 1–2 elements per screen.

---

## 4. Typography Hierarchy

- **Display / Heading Font:** [E.g., *Fraunces*, *Space Grotesk*, *Newsreader*, *General Sans*]
- **Body Font:** [E.g., *Inter*, *Plus Jakarta Sans*, *Geist*, *IBM Plex Sans*]
- **Code / Data Monospace:** [E.g., *JetBrains Mono*, *Geist Mono*, *Fira Code*]
- **Type Scale Rhythm:**
  - Hero Display: `clamp(2.25rem, 5vw, 3.75rem)`
  - Section Title: `clamp(1.5rem, 3vw, 2.25rem)`
  - Subhead / Card Title: `1.25rem`
  - Body Text: `1rem` (16px) with `line-height: 1.6`
  - Small / Caption: `0.875rem` (14px)

---

## 5. Antislop Dials (R-37)

Calibrate the intensity dials (1 = minimal/restrained, 3 = dynamic/expressive):

- **ENERGY (1 / 2 / 3):** `[Choose 1, 2, or 3]`  
  *(1 = Quiet, spacious, high breathing room | 2 = Confident, balanced contrast | 3 = Bold, high-energy, punchy)*
- **RHYTHM (1 / 2 / 3):** `[Choose 1, 2, or 3]`  
  *(1 = Uniform modular grid | 2 = Structured variation | 3 = Expressive editorial layout with intentional breaks)*
- **MOTION (1 / 2 / 3):** `[Choose 1, 2, or 3]`  
  *(1 = Instant, zero decorative animation | 2 = Functional 150ms micro-transitions | 3 = Staged interactive choreography)*

---

## 6. Layout & Sizing Rules

- **Container Max-Width:** `1200px` (or appropriate dashboard width)
- **Border Radii System:**
  - Buttons / Inputs: `6px` (sharp and intentional, not uniform pill shapes)
  - Cards / Modals: `8px`
- **Elevation:** Flat with `1px solid [Border Color]` by default. Shadows reserved exclusively for floating overlays and dialogs (R-12).

---

## 7. Voice & Copywriting Directives

- **Tone:** Direct, plainspoken, technically grounded. Every number or metric must be real or omitted (C-5).
- **Prohibited AI Tells:** Zero empty buzzwords (*unlock, elevate, empower, seamless, game-changer, landscape, delve*).
