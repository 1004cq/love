/** @type {import('next').NextConfig} */
const nextConfig = {
  images: {
    remotePatterns: [
      { protocol: "https", hostname: "img.gejiba.com" },
      { protocol: "https", hostname: "q1.qlogo.cn" },
    ],
    unoptimized: true,
  },
  experimental: {
    serverActions: {
      bodySizeLimit: "10mb",
    },
  },
  async redirects() {
    return [
      {
        source: "/login",
        destination: "/admin",
        permanent: false,
      },
    ];
  },
};

export default nextConfig;
