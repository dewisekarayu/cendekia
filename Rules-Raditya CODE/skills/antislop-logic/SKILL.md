---
name: antislop-logic
description: "Code logic, architecture, and dependency hygiene for AI coding agents: stop over-engineering, silent error swallowing, phantom dependencies, and god files."
allowed-tools: Read Write Edit Glob Grep
---
# antislop-logic

> Anti Slop: Rules for AI Coding Agents. Code Logic & Architecture skill

> Part of the antislop system. Read together with `skills/antislop/SKILL.md` (the core). While `antislop-code` protects comments, this skill guards the **executable logic and structural code** against recognizable AI-slop habits: over-engineering, silent error swallowing, unneeded third-party libraries, bloated monolithic files, and leaking mock data into production paths.

## How to use this skill

- Load together with the core whenever writing, refactoring, or architecting application logic.
- Every entry has the same shape: **Tell** (the pattern), **Why** (why it degrades code health), **Fix** (the pragmatic engineering solution), followed by an **Anti-Rationalization Table** at the end.
- **Scope rule:** Write the simplest workable solution that meets the requirement. Reject premature abstraction.

---

## Architectural Slop

### Premature Abstraction & Factory Mania

- **Tell:** Creating generic abstract classes, multiple interface layers, strategy patterns, or factory-of-factories for a single concrete implementation that only does one simple task.
- **Why:** AI models are trained on enterprise codebases where layers of abstraction often exist for legacy reasons. Injecting this into ordinary tasks bloats the call stack, obscures control flow, and creates cognitive overload without providing extensibility.
- **Fix:** Start with a simple, direct function. Follow the Rule of Three: do not abstract until you see the exact pattern repeated three times in production code. Clarity over cleverness.

### The Monolithic God File

- **Tell:** Shoving database queries, validation logic, UI state, helper functions, and React components into a single 800+ line file.
- **Why:** AI agents prefer appending code to the file currently in context rather than navigating or creating modular files. This results in tightly coupled, unmaintainable monoliths.
- **Fix:** Enforce the Rule of 300: if a file exceeds 300–400 lines or mixes concerns (e.g. data fetching + business rules + presentation), separate them into focused modules with explicit boundaries.

### Leaked Mock Data in Production Code

- **Tell:** Hardcoded dummy arrays (`const mockUsers = [{ id: 1, name: 'John Doe' }]`) left active inside production API endpoints, database functions, or production component states.
- **Why:** AI agents frequently use mocks while prototyping and forget to wire up real persistence or leave fallback mocks that mask broken API connections.
- **Fix:** Mocks belong strictly in test fixtures (`fixtures/` or `__mocks__/`). Any production code path must either interact with real data contracts or fail explicitly when disconnected.

---

## Error Handling & Reliability Slop

### Silent Error Swallowing

- **Tell:** Empty `catch` blocks, or catching errors only to log them with `console.error(error)` and returning an empty object/null without recovery or alerting.
  ```typescript
  // Typical AI Slop:
  try {
    await processPayment(orderId);
  } catch (err) {
    console.error("Payment failed", err);
    return null; // Silent failure!
  }
  ```
- **Why:** The AI writes this to make the function "never crash", but it creates phantom bugs where operations silently fail while the rest of the application assumes success.
- **Fix:** Fail fast and visibly. Handle known error types explicitly; rethrow unexpected errors or bubble them up to an error boundary / centralized error handler.

### The "Any" / Unsafe Type Escape

- **Tell:** Liberal use of `any`, `as unknown as Type`, `@ts-ignore`, or unvalidated type assertions instead of defining accurate schema types or input guards.
- **Why:** The agent chooses the path of least resistance to make the linter pass without understanding the underlying data contract.
- **Fix:** Define explicit schema contracts (e.g., Zod, Pydantic, or native TypeScript types). Parse data at system boundaries (API responses, form inputs, local storage).

---

## Dependency Hygiene

### Phantom & Bloated Dependencies

- **Tell:** Recommending or installing heavy external libraries (`lodash`, `moment`, `uuid`, `axios`) for operations natively supported in modern runtimes (`crypto.randomUUID()`, `fetch()`, `Intl.DateTimeFormat`, native array methods).
- **Why:** Training data contains years of legacy patterns prior to modern browser and runtime APIs.
- **Fix:** Audit dependencies before installing. If an operation takes fewer than 10 lines of standard native code, do not install a new package.

---

## Testing Hygiene

### Tautological Tests (Testing the Mock)

- **Tell:** Unit tests where every dependency is mocked to return a static value, and the assertion merely verifies that the mocked value was returned, testing zero actual business logic.
- **Why:** Agents optimize for green checkmarks and test coverage percentages rather than test validity.
- **Fix:** Test behavior, inputs, outputs, and edge cases. In unit tests, mock only external network/disk boundaries. If you cannot test the real logic of a function without mocking its internal implementation, refactor the function.

---

## Anti-Rationalization Table

AI agents often justify code slop with plausible-sounding excuses. When an agent attempts these justifications, reject them immediately:

| AI Excuse / Rationalization | Firm Rebuttal |
|---|---|
| *"I added this generic BaseRepository and Factory so the code is scalable for future databases."* | **Rejected.** You do not need it yet (YAGNI). Write the simplest direct query. Refactor when requirements actually demand multiple database drivers. |
| *"I wrapped the call in try/catch and returned null to prevent the application from crashing."* | **Rejected.** Silent failures hide critical bugs. Let errors bubble up to the nearest error boundary or return an explicit Result/Error type. |
| *"I installed lodash to safely deep-clone this object."* | **Rejected.** Modern runtimes have native `structuredClone()`. Do not add dependencies for native capabilities. |
| *"All tests are passing (100% coverage), so the feature is complete."* | **Rejected.** Passing tests that only test mocks prove nothing. Verify actual failure modes and edge cases with assertions that test real state changes. |

---

## Logic Skill Checklist

Run this alongside the core Delivery Gate before closing any coding task:

- [ ] **Simplicity:** Does every function and class serve an immediate, concrete requirement (no speculative abstraction)?
- [ ] **Error Visibility:** Are errors either properly recovered from or explicitly bubbled up (zero empty catches)?
- [ ] **Dependency Audit:** Were any new packages added? Could they be accomplished with standard native runtime APIs?
- [ ] **Data Authenticity:** Are there any hardcoded mock values left in production code paths?
- [ ] **Boundary Validation:** Are external inputs (APIs, query params, form data) validated with types or schema validators?
- [ ] **Meaningful Tests:** Do unit/integration tests verify actual logic and error conditions, rather than testing mocks?
