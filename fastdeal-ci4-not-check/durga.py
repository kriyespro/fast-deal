import os
import shutil
import subprocess
import sys

def clear_cache():
    print("🧹 Clearing CodeIgniter 4 cache...")
    # 1. Clear via spark command
    try:
        subprocess.run(["php", "spark", "cache:clear"], check=True)
    except Exception:
        # 2. Manual clear if spark fails
        cache_dir = os.path.join("writable", "cache")
        if os.path.exists(cache_dir):
            for filename in os.listdir(cache_dir):
                if filename != "index.html" and not filename.startswith("."):
                    file_path = os.path.join(cache_dir, filename)
                    try:
                        if os.path.isfile(file_path) or os.path.islink(file_path):
                            os.unlink(file_path)
                        elif os.path.isdir(file_path):
                            shutil.rmtree(file_path)
                    except Exception as e:
                        print(f"Failed to delete {file_path}. Reason: {e}")
    print("✅ Cache cleared.")

def start_server():
    print("🚀 Starting FastDeal development server...")
    try:
        subprocess.run(["php", "spark", "serve"], check=True)
    except KeyboardInterrupt:
        print("\n🛑 Server stopped.")
    except Exception as e:
        print(f"❌ Error starting server: {e}")

if __name__ == "__main__":
    clear_cache()
    if len(sys.argv) > 1 and sys.argv[1] == "clear":
        pass # just clear
    else:
        start_server()
