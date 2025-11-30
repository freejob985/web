// Firebase Frontend Integration Examples
// مثال على التكامل مع Firebase في تطبيق JavaScript/React Native

/**
 * ============================================
 * 1. إعداد Firebase في تطبيق React Native
 * ============================================
 */

// Install packages first:
// npm install @react-native-firebase/app @react-native-firebase/messaging

import messaging from '@react-native-firebase/messaging';
import AsyncStorage from '@react-native-async-storage/async-storage';

/**
 * ============================================
 * 2. طلب إذن الإشعارات والحصول على Firebase Token
 * ============================================
 */
export const requestFirebasePermission = async (userId, userType = 'user') => {
    try {
        // طلب إذن الإشعارات
        const authStatus = await messaging().requestPermission();
        const enabled =
            authStatus === messaging.AuthorizationStatus.AUTHORIZED ||
            authStatus === messaging.AuthorizationStatus.PROVISIONAL;

        if (enabled) {
            console.log('Authorization status:', authStatus);

            // الحصول على Firebase token
            const fcmToken = await messaging().getToken();

            if (fcmToken) {
                console.log('Firebase Token:', fcmToken);

                // حفظ Firebase token في AsyncStorage
                await AsyncStorage.setItem('fcm_token', fcmToken);

                // إرسال Firebase token للسيرفر
                await updateFcmTokenToServer(userId, userType, fcmToken);

                return fcmToken;
            }
        } else {
            console.log('Permission denied');
        }
    } catch (error) {
        console.error('Error requesting Firebase permission:', error);
    }
};

/**
 * ============================================
 * 3. إرسال Firebase Token للسيرفر
 * ============================================
 */
const updateFcmTokenToServer = async (userId, userType, fcmToken) => {
    try {
        const response = await fetch(
            'https://0d2c7ff974.nxcli.io/api/v1/salla-delivery/chat/update-fcm-token',
            {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    user_id: userId,
                    user_type: userType, // 'user' or 'driver'
                    fcm_token: fcmToken,
                }),
            }
        );

        const data = await response.json();
        console.log('FCM Token updated:', data);
        return data;
    } catch (error) {
        console.error('Error updating FCM token:', error);
    }
};

/**
 * ============================================
 * 4. الاستماع للإشعارات في الخلفية (Background)
 * ============================================
 */
export const setupBackgroundMessageHandler = () => {
    messaging().setBackgroundMessageHandler(async remoteMessage => {
        console.log('Message handled in the background!', remoteMessage);

        // معالجة الإشعار في الخلفية
        const { notification, data } = remoteMessage;

        if (data.type === 'new_message') {
            // يمكنك تحديث عدد الرسائل غير المقروءة في التطبيق
            console.log('New message from order:', data.order_number);
        }
    });
};

/**
 * ============================================
 * 5. الاستماع للإشعارات عندما التطبيق مفتوح (Foreground)
 * ============================================
 */
export const setupForegroundMessageHandler = (navigation) => {
    const unsubscribe = messaging().onMessage(async remoteMessage => {
        console.log('A new FCM message arrived!', remoteMessage);

        const { notification, data } = remoteMessage;

        // عرض إشعار محلي أو تنبيه
        if (notification) {
            alert(`${notification.title}\n${notification.body}`);
        }

        // التنقل إلى صفحة الشات إذا كان نوع الإشعار "new_message"
        if (data.type === 'new_message') {
            // يمكنك التنقل مباشرة إلى صفحة الشات
            navigation.navigate('ChatScreen', {
                orderId: data.order_id,
                orderNumber: data.order_number,
            });
        }
    });

    return unsubscribe;
};

/**
 * ============================================
 * 6. معالجة فتح الإشعار (Notification Opened)
 * ============================================
 */
export const setupNotificationOpenedHandler = (navigation) => {
    // When app is in background and user taps notification
    messaging().onNotificationOpenedApp(remoteMessage => {
        console.log('Notification caused app to open from background:', remoteMessage);

        const { data } = remoteMessage;

        if (data.type === 'new_message') {
            navigation.navigate('ChatScreen', {
                orderId: data.order_id,
                orderNumber: data.order_number,
            });
        }
    });

    // When app is completely closed and user taps notification
    messaging()
        .getInitialNotification()
        .then(remoteMessage => {
            if (remoteMessage) {
                console.log('Notification caused app to open from quit state:', remoteMessage);

                const { data } = remoteMessage;

                if (data.type === 'new_message') {
                    navigation.navigate('ChatScreen', {
                        orderId: data.order_id,
                        orderNumber: data.order_number,
                    });
                }
            }
        });
};

/**
 * ============================================
 * 7. إرسال رسالة جديدة مع Firebase
 * ============================================
 */
export const sendMessage = async (orderId, message, senderType, senderId, attachment = null) => {
    try {
        const formData = new FormData();
        formData.append('message', message);
        formData.append('sender_type', senderType);
        formData.append('sender_id', senderId);

        if (attachment) {
            formData.append('attachment', {
                uri: attachment.uri,
                type: attachment.type,
                name: attachment.fileName,
            });
        }

        const response = await fetch(
            `https://0d2c7ff974.nxcli.io/api/v1/salla-delivery/chat/send/${orderId}`,
            {
                method: 'POST',
                body: formData,
                headers: {
                    'Content-Type': 'multipart/form-data',
                },
            }
        );

        const data = await response.json();

        if (data.success) {
            console.log('Message sent successfully:', data.data);
            return data.data;
        } else {
            console.error('Failed to send message:', data.message);
            throw new Error(data.message);
        }
    } catch (error) {
        console.error('Error sending message:', error);
        throw error;
    }
};

/**
 * ============================================
 * 8. الحصول على الرسائل
 * ============================================
 */
export const getMessages = async (orderId) => {
    try {
        const response = await fetch(
            `https://0d2c7ff974.nxcli.io/api/v1/salla-delivery/chat/messages/${orderId}`
        );

        const data = await response.json();

        if (data.success) {
            return data.data.messages;
        } else {
            throw new Error(data.message);
        }
    } catch (error) {
        console.error('Error fetching messages:', error);
        throw error;
    }
};

/**
 * ============================================
 * 9. تحديد الرسالة كمقروءة
 * ============================================
 */
export const markMessageAsRead = async (messageId) => {
    try {
        const response = await fetch(
            `https://0d2c7ff974.nxcli.io/api/v1/salla-delivery/chat/mark-read/${messageId}`,
            {
                method: 'POST',
            }
        );

        const data = await response.json();
        return data.success;
    } catch (error) {
        console.error('Error marking message as read:', error);
        return false;
    }
};

/**
 * ============================================
 * 10. الحصول على عدد الرسائل غير المقروءة
 * ============================================
 */
export const getUnreadCount = async (orderId, recipientType) => {
    try {
        const response = await fetch(
            `https://0d2c7ff974.nxcli.io/api/v1/salla-delivery/chat/unread-count/${orderId}/${recipientType}`
        );

        const data = await response.json();

        if (data.success) {
            return data.data.unread_count;
        }
        return 0;
    } catch (error) {
        console.error('Error fetching unread count:', error);
        return 0;
    }
};

/**
 * ============================================
 * 11. مثال على استخدام Hook في React Native Component
 * ============================================
 */
import { useEffect, useState } from 'react';
import { useNavigation } from '@react-navigation/native';

export const useChatWithFirebase = (orderId, userId, userType) => {
    const [messages, setMessages] = useState([]);
    const [unreadCount, setUnreadCount] = useState(0);
    const [loading, setLoading] = useState(false);
    const navigation = useNavigation();

    useEffect(() => {
        // إعداد Firebase عند تحميل Component
        initializeFirebase();

        // تحميل الرسائل
        loadMessages();

        // تحميل عدد غير المقروءة
        loadUnreadCount();

        // الاستماع للإشعارات
        const unsubscribe = setupForegroundMessageHandler(navigation);

        return () => unsubscribe();
    }, [orderId]);

    const initializeFirebase = async () => {
        await requestFirebasePermission(userId, userType);
        setupBackgroundMessageHandler();
        setupNotificationOpenedHandler(navigation);
    };

    const loadMessages = async () => {
        setLoading(true);
        try {
            const msgs = await getMessages(orderId);
            setMessages(msgs);
        } catch (error) {
            console.error('Error loading messages:', error);
        }
        setLoading(false);
    };

    const loadUnreadCount = async () => {
        const count = await getUnreadCount(orderId, userType);
        setUnreadCount(count);
    };

    const handleSendMessage = async (message, attachment = null) => {
        try {
            const newMessage = await sendMessage(orderId, message, userType, userId, attachment);
            setMessages([...messages, newMessage]);
            return true;
        } catch (error) {
            console.error('Error sending message:', error);
            return false;
        }
    };

    const handleMarkAsRead = async (messageId) => {
        const success = await markMessageAsRead(messageId);
        if (success) {
            await loadUnreadCount();
        }
    };

    return {
        messages,
        unreadCount,
        loading,
        sendMessage: handleSendMessage,
        markAsRead: handleMarkAsRead,
        refreshMessages: loadMessages,
    };
};

/**
 * ============================================
 * 12. مثال على استخدام في Component
 * ============================================
 */

/*
import React from 'react';
import { View, Text, FlatList, TextInput, Button } from 'react-native';
import { useChatWithFirebase } from './useChatWithFirebase';

const ChatScreen = ({ route }) => {
  const { orderId } = route.params;
  const userId = 1; // من authentication
  const userType = 'user'; // أو 'driver'
  
  const {
    messages,
    unreadCount,
    loading,
    sendMessage,
    markAsRead,
    refreshMessages,
  } = useChatWithFirebase(orderId, userId, userType);

  const [newMessage, setNewMessage] = React.useState('');

  const handleSend = async () => {
    if (newMessage.trim()) {
      await sendMessage(newMessage);
      setNewMessage('');
    }
  };

  return (
    <View style={{ flex: 1 }}>
      <Text>عدد الرسائل غير المقروءة: {unreadCount}</Text>
      
      <FlatList
        data={messages}
        keyExtractor={(item) => item.id.toString()}
        renderItem={({ item }) => (
          <View>
            <Text>{item.message}</Text>
            <Text>{item.created_at}</Text>
          </View>
        )}
      />
      
      <View>
        <TextInput
          value={newMessage}
          onChangeText={setNewMessage}
          placeholder="اكتب رسالتك..."
        />
        <Button title="إرسال" onPress={handleSend} />
      </View>
    </View>
  );
};

export default ChatScreen;
*/
