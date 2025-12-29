use rusqlite::{Connection, params};
use serde::Serialize;
use tauri::Manager;

#[tauri::command]
fn greet(name: &str) -> String {
    format!("Hello, {}! You've been greeted from Rust!", name)
}

#[derive(Serialize)]
struct Activity {
    id: i64,
    title: String,
}

// helper: open DB in app data dir
fn get_db(app: &tauri::AppHandle) -> Result<Connection, String> {
    let app_dir = app
        .path()
        .app_data_dir()
        .map_err(|e| e.to_string())?;

    std::fs::create_dir_all(&app_dir).map_err(|e| e.to_string())?;

    let db_path = app_dir.join("daily_activity.db");
    Connection::open(db_path).map_err(|e| e.to_string())
}

#[tauri::command]
fn add_activity(app: tauri::AppHandle, title: String) -> Result<(), String> {
    let conn = get_db(&app)?;

    conn.execute(
        "CREATE TABLE IF NOT EXISTS activities (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            title TEXT NOT NULL
        )",
        [],
    ).map_err(|e| e.to_string())?;

    conn.execute(
        "INSERT INTO activities (title) VALUES (?1)",
        params![title],
    ).map_err(|e| e.to_string())?;

    Ok(())
}

#[tauri::command]
fn get_activities(app: tauri::AppHandle) -> Result<Vec<Activity>, String> {
    let conn = get_db(&app)?;

    let mut stmt = conn
        .prepare("SELECT id, title FROM activities ORDER BY id DESC")
        .map_err(|e| e.to_string())?;

    let rows = stmt
        .query_map([], |row| {
            Ok(Activity {
                id: row.get(0)?,
                title: row.get(1)?,
            })
        })
        .map_err(|e| e.to_string())?;

    let mut activities = Vec::new();
    for a in rows {
        activities.push(a.map_err(|e| e.to_string())?);
    }

    Ok(activities)
}

#[cfg_attr(mobile, tauri::mobile_entry_point)]
pub fn run() {
    tauri::Builder::default()
        .plugin(tauri_plugin_opener::init())
        .invoke_handler(tauri::generate_handler![
            greet,
            add_activity,
            get_activities
        ])
        .run(tauri::generate_context!())
        .expect("error while running tauri application");
}
