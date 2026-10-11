export const WEATHER_OPTIONS = [
  { value: "sunny", label: "晴", icon: "☀" },
  { value: "cloudy", label: "云", icon: "☁" },
  { value: "rainy", label: "雨", icon: "☂" },
  { value: "snowy", label: "雪", icon: "❄" },
] as const;

export const MOOD_OPTIONS = [
  { value: "happy", label: "开心", icon: "☺" },
  { value: "calm", label: "平静", icon: "✧" },
  { value: "love", label: "心动", icon: "♡" },
  { value: "miss", label: "想念", icon: "☽" },
  { value: "tired", label: "疲惫", icon: "☾" },
] as const;

export type WeatherValue = (typeof WEATHER_OPTIONS)[number]["value"];
export type MoodValue = (typeof MOOD_OPTIONS)[number]["value"];

export function weatherMeta(value?: string | null) {
  return (
    WEATHER_OPTIONS.find((item) => item.value === value) || WEATHER_OPTIONS[0]
  );
}

export function moodMeta(value?: string | null) {
  return MOOD_OPTIONS.find((item) => item.value === value) || MOOD_OPTIONS[0];
}

const MONTHS = [
  "JAN",
  "FEB",
  "MAR",
  "APR",
  "MAY",
  "JUN",
  "JUL",
  "AUG",
  "SEP",
  "OCT",
  "NOV",
  "DEC",
];

const MONTHS_ZH = [
  "一月",
  "二月",
  "三月",
  "四月",
  "五月",
  "六月",
  "七月",
  "八月",
  "九月",
  "十月",
  "十一月",
  "十二月",
];

export function diaryDateParts(date: Date | string) {
  const d = typeof date === "string" ? new Date(date) : date;
  return {
    day: d.getDate().toString().padStart(2, "0"),
    month: MONTHS[d.getMonth()],
    monthZh: MONTHS_ZH[d.getMonth()],
    year: d.getFullYear(),
    time: new Intl.DateTimeFormat("zh-CN", {
      hour: "2-digit",
      minute: "2-digit",
      hour12: false,
    }).format(d),
  };
}
