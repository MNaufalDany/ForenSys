import os
import time

from selenium import webdriver
from selenium.webdriver.common.by import By
from selenium.webdriver.support.ui import WebDriverWait
from selenium.webdriver.support import expected_conditions as EC

BASE_URL = "http://127.0.0.1:8000"

LOGIN_EMAIL = "dokterAP@forensys.test"
LOGIN_PASSWORD = "dokter123"

CASE_ID = "1"
CASE_NUMBER = "AG-2026-003"

os.makedirs("selenium/screenshots", exist_ok=True)

driver = webdriver.Chrome()
wait = WebDriverWait(driver, 15)


def screenshot(name):
    driver.save_screenshot(f"selenium/screenshots/{name}.png")


def login():
    driver.get(f"{BASE_URL}/login")

    wait.until(
        EC.presence_of_element_located((By.NAME, "email"))
    )

    driver.find_element(By.NAME, "email").send_keys(LOGIN_EMAIL)
    driver.find_element(By.NAME, "password").send_keys(LOGIN_PASSWORD)

    driver.find_element(By.ID, "login-button").click()

    time.sleep(3)

    screenshot("ST0_Login")


def click_button_by_text(text):
    button = wait.until(
        EC.element_to_be_clickable(
            (By.XPATH, f"//button[contains(., '{text}')]")
        )
    )
    driver.execute_script("arguments[0].click();", button)


def visible_input(name):
    inputs = driver.find_elements(By.NAME, name)

    for item in inputs:
        if item.is_displayed():
            return item

    raise Exception(f"Input {name} tidak ditemukan")


try:

    driver.set_window_size(1366, 768)

    # LOGIN
    login()

    # ST1 Search Case
    driver.get(f"{BASE_URL}/cases")

    wait.until(
        EC.presence_of_element_located((By.NAME, "search"))
    )

    search = driver.find_element(By.NAME, "search")
    search.clear()
    search.send_keys(CASE_NUMBER)

    screenshot("ST1_Search_Case")

    # ST2 Open Examination
    driver.get(f"{BASE_URL}/cases/{CASE_ID}/examination")

    time.sleep(2)

    screenshot("ST2_Examination_Page")

    # ST3 View Completed Case
    driver.get(f"{BASE_URL}/cases/{CASE_ID}")

    time.sleep(2)

    screenshot("ST3_Completed_Case")

    # =========================
    # ST4 Completed -> Verified
    # =========================

    click_button_by_text("Verify Case")

    time.sleep(1)

    case_input = visible_input("case_number_confirmation")
    password_input = visible_input("doctor_password")

    case_input.clear()
    case_input.send_keys(CASE_NUMBER)

    password_input.clear()
    password_input.send_keys(LOGIN_PASSWORD)

    click_button_by_text("Confirm Verify")

    time.sleep(3)

    screenshot("ST4_Completed_To_Verified")

    # kembali ke detail case
    driver.get(f"{BASE_URL}/cases/{CASE_ID}")

    time.sleep(2)

    # =====================
    # ST5 Verified -> Close
    # =====================

    click_button_by_text("Close This Case")

    time.sleep(1)

    case_input = visible_input("case_number_confirmation")
    password_input = visible_input("doctor_password")

    case_input.clear()
    case_input.send_keys(CASE_NUMBER)

    password_input.clear()
    password_input.send_keys(LOGIN_PASSWORD)

    click_button_by_text("Confirm Close")

    time.sleep(3)

    screenshot("ST5_Verified_To_Closed")

    print("\nSELENIUM TEST SUCCESS\n")

except Exception as e:
    print("\nERROR:")
    print(e)
    screenshot("ERROR_SCREEN")

finally:
    time.sleep(3)
    driver.quit()
