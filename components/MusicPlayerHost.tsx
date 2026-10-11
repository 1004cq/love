import { MusicPlayer } from "@/components/MusicPlayer";
import { prisma } from "@/lib/prisma";

export async function MusicPlayerHost() {
  const tracks = await prisma.musicTrack.findMany({
    orderBy: { sortOrder: "asc" },
  });

  return (
    <MusicPlayer
      tracks={tracks.map((track) => ({
        id: track.id,
        title: track.title,
        artist: track.artist,
        url: track.url,
        coverUrl: track.coverUrl,
      }))}
    />
  );
}
