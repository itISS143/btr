from pytube import YouTube

def download_youtube_video(url, save_path):
    try:
        yt = YouTube(url)
        video = yt.streams.filter(progressive=True, file_extension='mp4').first()
        video.download(save_path)
        print("Video downloaded successfully!")
    except Exception as e:
        print(f"Error downloading video: {e}")

if __name__ == "__main__":
    # Example usage:
    video_url = input("Enter the YouTube video URL: ")
    save_path = input("Enter the path to save the video (or leave blank for current directory): ").strip() or '.'
    download_youtube_video(video_url, save_path)
