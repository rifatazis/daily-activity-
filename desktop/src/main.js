import { invoke } from "@tauri-apps/api/core";

console.log("main.js loaded");
console.log("invoke type:", typeof invoke);

// ===== GREET =====
const greetForm = document.querySelector("#greet-form");
const greetInput = document.querySelector("#greet-input");
const greetMsg = document.querySelector("#greet-msg");

greetForm?.addEventListener("submit", async (e) => {
  e.preventDefault();

  try {
    const result = await invoke("greet", {
      name: greetInput.value,
    });
    greetMsg.textContent = result;
  } catch (err) {
    console.error("greet error:", err);
  }
});

// ===== ACTIVITY =====
const activityForm = document.querySelector("#activity-form");
const activityInput = document.querySelector("#activity-input");
const list = document.querySelector("#activity-list");

activityForm?.addEventListener("submit", async (e) => {
  e.preventDefault();

  try {
    await invoke("add_activity", {
      title: activityInput.value,
    });
    activityInput.value = "";
    await loadActivities();
  } catch (err) {
    console.error("add_activity error:", err);
  }
});

async function loadActivities() {
  console.log("loadActivities called");

  try {
    const activities = await invoke("get_activities");
    console.log("activities:", activities);

    list.innerHTML = "";
    activities.forEach((a) => {
      const li = document.createElement("li");
      li.textContent = a.title;
      list.appendChild(li);
    });
  } catch (err) {
    console.error("get_activities error:", err);
  }
}

loadActivities();
