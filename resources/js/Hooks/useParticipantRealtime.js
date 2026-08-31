import { useState, useEffect, useRef } from 'react';
import axios from 'axios';

export function useParticipantRealtime({ bimtekId = null, onParticipantRegistered = null, onAttendanceRecorded = null } = {}) {
  const [isConnected, setIsConnected] = useState(true);
  const [latestNotification, setLatestNotification] = useState(null);
  const [recentEvents, setRecentEvents] = useState([]);
  
  const lastTimeRef = useRef(Date.now() / 1000);
  const processedEventIds = useRef(new Set());
  const intervalRef = useRef(null);

  useEffect(() => {
    if (typeof window === 'undefined') return;

    let isSubscribed = true;

    const checkRealtimeEvents = async () => {
      try {
        const response = await axios.get('/admin/realtime-poll', {
          params: { since: lastTimeRef.current },
          timeout: 4000,
        });

        if (!isSubscribed) return;

        setIsConnected(true);
        const data = response.data;

        if (data?.current_time) {
          lastTimeRef.current = data.current_time;
        }

        if (Array.isArray(data?.events) && data.events.length > 0) {
          data.events.forEach((ev) => {
            if (processedEventIds.current.has(ev.id)) return;
            processedEventIds.current.add(ev.id);

            // Handle ParticipantRegistered
            if (ev.event === 'ParticipantRegistered') {
              const pData = ev.data;
              if (bimtekId && Number(pData.bimtek_id) !== Number(bimtekId)) {
                return;
              }

              setLatestNotification(pData);
              setRecentEvents((prev) => [pData, ...prev].slice(0, 20));

              if (typeof onParticipantRegistered === 'function') {
                onParticipantRegistered(pData);
              }

              setTimeout(() => {
                setLatestNotification((current) => (current?.id === pData.id ? null : current));
              }, 7000);
            }

            // Handle AttendanceRecorded
            if (ev.event === 'AttendanceRecorded') {
              if (typeof onAttendanceRecorded === 'function') {
                onAttendanceRecorded(ev.data);
              }
            }
          });
        }
      } catch (err) {
        if (isSubscribed) {
          // If 403 or unauthorized, stop
          if (err.response?.status === 403 || err.response?.status === 401) {
            setIsConnected(false);
          }
        }
      }
    };

    // Initial check
    checkRealtimeEvents();

    // Regular light interval check (every 2.5s)
    intervalRef.current = setInterval(checkRealtimeEvents, 2500);

    return () => {
      isSubscribed = false;
      if (intervalRef.current) {
        clearInterval(intervalRef.current);
      }
    };
  }, [bimtekId, onParticipantRegistered, onAttendanceRecorded]);

  return {
    isConnected,
    latestNotification,
    clearNotification: () => setLatestNotification(null),
    recentEvents,
  };
}
