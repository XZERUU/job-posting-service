// ============================================================
// Register Screen - Job Seeker self-registration only
// Employer accounts are created exclusively by PESO Admin.
// ============================================================
import React, { useState } from 'react';
import {
  View, Text, StyleSheet, ScrollView, KeyboardAvoidingView, Platform, Alert, TouchableOpacity,
} from 'react-native';
import { useRouter } from 'expo-router';
import { Button, Input, Card } from '../src/components/ui';
import { api, getApiError } from '../src/api/client';
import { useAuth } from '../src/context/AuthContext';
import { Colors, Spacing, FontSize, Radius } from '../src/constants/theme';

export default function Register() {
  const router = useRouter();
  const { login } = useAuth();
  const [firstName, setFirstName] = useState('');
  const [lastName, setLastName] = useState('');
  const [email, setEmail] = useState('');
  const [password, setPassword] = useState('');
  const [confirmPassword, setConfirmPassword] = useState('');
  const [role, setRole] = useState<'job_seeker' | 'employer'>('job_seeker');
  const [loading, setLoading] = useState(false);

  const handleRegister = async () => {
    if (!email || !password) {
      Alert.alert('Required', 'Email and password are required.');
      return;
    }
    if (password.length < 6) {
      Alert.alert('Weak password', 'Password must be at least 6 characters.');
      return;
    }
    if (password !== confirmPassword) {
      Alert.alert('Mismatch', 'Passwords do not match.');
      return;
    }
    if (!firstName || !lastName) {
      Alert.alert('Required', 'First and last name are required.');
      return;
    }

    setLoading(true);
    try {
      const res = await api.post('/auth/register', {
        email: email.trim(),
        password,
        role: role,
        profile: { first_name: firstName, last_name: lastName },
      });
      await login(res.data.token, res.data.user);
      const userRole = res.data.user.role;
      if (userRole === 'job_seeker') router.replace('/(seeker)/dashboard');
      else if (userRole === 'employer') router.replace('/(employer)/dashboard');
      else router.replace('/(admin)/dashboard');
    } catch (err: any) {
      Alert.alert('Registration Failed', getApiError(err));
    } finally {
      setLoading(false);
    }
  };

  return (
    <KeyboardAvoidingView
      behavior={Platform.OS === 'ios' ? 'padding' : 'height'}
      style={{ flex: 1, backgroundColor: Colors.primaryDark }}
    >
      <ScrollView contentContainerStyle={styles.content} keyboardShouldPersistTaps="handled">
        <View style={styles.topBlock}>
          <Text style={styles.title}>Create account</Text>
          <Text style={styles.subtitle}>Register to access PESO-Link MisOr job opportunities.</Text>
        </View>

        <View style={styles.sheet}>

          <View style={styles.nameRow}>
            <View style={{ flex: 1 }}>
              <Input testID="reg-firstname" label="First name" value={firstName} onChangeText={setFirstName} placeholder="Juan" autoCapitalize="words" />
            </View>
            <View style={{ width: Spacing.sm }} />
            <View style={{ flex: 1 }}>
              <Input testID="reg-lastname" label="Last name" value={lastName} onChangeText={setLastName} placeholder="dela Cruz" autoCapitalize="words" />
            </View>
          </View>

          <Input testID="reg-email" label="Email address" value={email} onChangeText={setEmail} placeholder="you@example.com" keyboardType="email-address" />
          <Input testID="reg-password" label="Password" value={password} onChangeText={setPassword} secureTextEntry placeholder="••••••••" />
          <Input testID="reg-confirm" label="Confirm password" value={confirmPassword} onChangeText={setConfirmPassword} secureTextEntry placeholder="••••••••" />
          
          <Text style={styles.label}>Register as</Text>
          <View style={styles.segment}>
            <TouchableOpacity onPress={() => setRole('job_seeker')} activeOpacity={0.8} style={[styles.segmentItem, role === 'job_seeker' && styles.segmentActive, { borderTopLeftRadius: Radius.sm, borderBottomLeftRadius: Radius.sm, zIndex: role === 'job_seeker' ? 1 : 0 }]}>
              <Text style={[styles.segmentText, role === 'job_seeker' && styles.segmentTextActive]}>Job seeker</Text>
            </TouchableOpacity>
            <TouchableOpacity onPress={() => setRole('employer')} activeOpacity={0.8} style={[styles.segmentItem, role === 'employer' && styles.segmentActive, { borderTopRightRadius: Radius.sm, borderBottomRightRadius: Radius.sm, marginLeft: -1, zIndex: role === 'employer' ? 1 : 0 }]}>
              <Text style={[styles.segmentText, role === 'employer' && styles.segmentTextActive]}>Employer</Text>
            </TouchableOpacity>
          </View>

          <Button testID="reg-submit" title="Create Account" onPress={handleRegister} loading={loading} style={{ marginTop: Spacing.sm }} />

          <Text style={styles.linkText} onPress={() => router.push('/login')} testID="go-login">
            Already have an account? <Text style={styles.link}>Sign in</Text>
          </Text>
        </View>
      </ScrollView>
    </KeyboardAvoidingView>
  );
}

const styles = StyleSheet.create({
  content: { flexGrow: 1, backgroundColor: Colors.lightBg },
  topBlock: {
    backgroundColor: Colors.primaryDark,
    paddingHorizontal: Spacing.lg,
    paddingTop: Spacing.xxl,
    paddingBottom: Spacing.xl,
  },
  kicker: { color: Colors.cardHighlight, fontSize: FontSize.xs, fontWeight: '800', marginBottom: Spacing.md },
  title: { fontSize: FontSize.xxxl, fontWeight: '900', color: Colors.white },
  subtitle: { fontSize: FontSize.md, color: Colors.cardHighlight, marginTop: 6, lineHeight: 22 },
  sheet: {
    flex: 1,
    backgroundColor: Colors.surface,
    borderTopLeftRadius: Radius.xl,
    borderTopRightRadius: Radius.xl,
    padding: Spacing.lg,
    marginTop: -Spacing.md,
  },
  nameRow: { flexDirection: 'row' },
  label: { fontSize: FontSize.sm, fontWeight: '700', color: Colors.textDark, marginBottom: 6 },
  segment: { flexDirection: 'row', marginBottom: Spacing.md },
  segmentItem: { flex: 1, paddingVertical: 12, alignItems: 'center', justifyContent: 'center', borderWidth: 1, borderColor: Colors.borderSoft, backgroundColor: Colors.white },
  segmentActive: { borderColor: Colors.primary, backgroundColor: Colors.cardHighlight },
  segmentText: { color: Colors.gray, fontSize: FontSize.sm, fontWeight: '700' },
  segmentTextActive: { color: Colors.primaryDark },
  linkText: { textAlign: 'center', color: Colors.textDark, fontSize: FontSize.sm, marginTop: Spacing.lg },
  link: { color: Colors.primary, fontWeight: '800' },
});
