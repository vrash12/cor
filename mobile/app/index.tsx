import { useRouter } from 'expo-router';
import { useEffect, useState } from 'react';
import { ActivityIndicator, View } from 'react-native';
import { useAuth } from '../stores/auth';

export default function Index() {
  const router = useRouter();
  const { loadMe, role } = useAuth();
  const [boot, setBoot] = useState(true);

  useEffect(() => {
    (async () => {
      await loadMe();     // fetch /api/me if token exists
      setBoot(false);
    })();
  }, []);

  useEffect(() => {
    if (boot) return;
    // Default to customer if no role yet
    if (role === 'farmer') router.replace('/farmer');
    else if (role === 'cooperativeadmin') router.replace('/admin');
    else router.replace('/customer');
  }, [boot, role]);

  return (
    <View style={{ flex: 1, alignItems:'center', justifyContent:'center' }}>
      <ActivityIndicator />
    </View>
  );
}
